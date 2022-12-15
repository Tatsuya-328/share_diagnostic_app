<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLog;
use Auth;
use DB;
use Exception;
use Log;

class UserController extends Controller
{
    /**
     * route /user
     */
    public function list(Request $request)
    {
        $param = ['name' => '', 'email' => '', 'status' => ''];
        $query = User::query()->withTrashed();
        if (!empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
            $param['name'] = $request->input('name');
        }
        if (!empty($request->input('email'))) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
            $param['email'] = $request->input('email');
        }
        if (!empty($request->input('status'))) {
            $query->where('status', $request->input('status'));
            $param['status'] = $request->input('status');
        }
        $list = $query->paginate(10);

        return view('partner.users.list', [
            'list' => $list,
            'param' => $param,
            'status_labels' => array_merge([''], User::STATUS_LABELS),
        ]);
    }

    /**
     * route /user/{id} (GET)
     */
    public function detail(Request $request, $id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        if (empty($user)) {
            abort(404);
        }

        return view('partner.users.detail', ['user' => $user]);
    }
    /**
     * route /user/{id} (POST)
     */
    public function storeMemo(Request $request, $id)
    {
        if ($request->has('btn-ban')) {
            $user = User::withTrashed()->where('id', $id)->first();
            if (empty($user->is_ban)) {
                $user->is_ban = true; //停止
                $user->deleted_at = date_create();
                $request->session()->flash('message', 'アカウントを停止しました。');
            } else {
                $user->is_ban = false; //復帰
                $user->deleted_at = null;
                $request->session()->flash('message', 'アカウントを復帰しました。');
            }
            $user->save();

        } elseif ($request->has('btn-save')) {
            $this->validate($request, [
                'memo' => 'max:1000', //一応
            ]);
            User::withTrashed()
                ->where('id', $id)
                ->update(['memo' => $request->input('memo')]);
            $request->session()->flash('message', 'メモを保存しました。');
        }
        UserLog::store($request);

        return redirect('user/' . $id);
    }

    /**
     * route /user/log
     */
    public function log(Request $request)
    {
        $searchInfo = [
            'user_id' => '=',
            'company_id' => '=',
            'action' => 'like',
            'log' => 'like',
        ];

        $query = UserLog::query()->with(['user' => function($query) use($request) {
            $query->withTrashed();
        }]);
        if ($request->has('search_name')) {
            $query->whereHas('user', function($query) use($request) {
                $query->where('name', 'like', '%' . trimSpace($request->input('search_name')) . '%');
            });
        }
        $order = $request->input('order') ?? $this->defaultListOrder;
        $sortby = $request->input('sort') ?? "created_at";
        $request->merge(['sort' => $sortby]);
        $linkAppends = ['sort' => $sortby, 'order' => $order];
        $searchColumns = [];
        $searchColumns["search_name"] = null;
        if ($request->has('search_name')) {
            $linkAppends["search_name"] = $request->input('search_name');
            $searchColumns["search_name"] = $request->input('search_name');
        }

        list($list, $links, $searchColumns) = $this->requestFormSearch($request, $query, $searchInfo, true, $linkAppends, $searchColumns);

        return view('partner.users.log', [
            'logs' => $list,
            'links' => $links,
            'search' => $searchColumns,
            'actions' => UserLog::SEARCH_ACTIONS,
            'searchActions' => UserLog::SEARCH_ACTIONS]);
    }

    /**
     * route user/edit (GET)
     */
    public function edit()
    {
        $user = Auth::user();
        return view('partner.users.edit', ['user' => $user]);
    }
    /**
     * route user/edit (POST)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'password' => 'nullable|string|regex:/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]+\z/i|min:8|max:20|confirmed',
        ]);

        $user->name = $request->input('name');
        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        UserLog::store($request);
        $request->session()->now('message', 'ユーザ情報を変更しました。');

        return view('partner.users.edit', ['user' => $user]);
    }

    /**
     * route user/email (GET)
     */
    public function email()
    {
        $user = Auth::user();
        return view('partner.users.email', ['user' => $user]);
    }
    /**
     * route user/email (POST)
     */
    public function changeEmail(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users')->where(function ($query) {
                    return $query->where("status", "=", User::STATUS_TYPE_MEMBER);
                }),
                Rule::unique('users')->where(function ($query) {
                    return $query->where("is_ban", true);
                })
            ],
        ]);

        DB::beginTransaction();
        try {
            $token = User::generateVerifyToken();
            $user->email_verify_token = $token;
            $user->change_email = $request->input('email');
            $user->change_email_requested_at = date_create();
            $user->save();
            $user->changeEmailNotification(); //メール通知
            DB::commit();
        } catch(Exception $e) {
            Log::error($e);
            DB::rollback();
            return redirect()->back()->with(['status' => false, 'message' => User::getMailErrorMessage($e)]);
        }

        UserLog::store($request);
        return redirect('user/email/change');
    }

    /**
     * route user/email/change (GET)
     */
    public function sentTokenMail()
    {
        $user = Auth::user();
        return view('partner.users.email_change', ['user' => $user]);
    }

    /**
     * route /user/leave (GET)
     */
    public function leave()
    {
        return view('partner.users.leave');
    }

    /**
     * route /user/leave (POST)
     */
    public function leaveExec(Request $request)
    {
        $user = Auth::user();
        $user->leave();

        return redirect('bye');
    }

}

