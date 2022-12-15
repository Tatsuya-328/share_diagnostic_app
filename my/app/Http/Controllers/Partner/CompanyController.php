<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyChangeRequest;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Railroad;
use App\Models\Station;
use App\Models\UsersCompanies;
use App\Models\Companyfacility;
use App\Models\PartnerImage;
use App\Models\Style;
use App\Models\User;
use App\Models\UsersCompany;
use App\Models\CompaniesStyle;
use App\Models\UserLog;
use App\Policies\CompanyPolicy;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Exception;
use App\Events\CompanyRegisterEvent;
use App\Events\CompanyRequestBaseEvent;
use App\Events\CompanyDetail1UpdateEvent;
use App\Events\CompanyApplyEvent;
use Log;
use Agent;

use Illuminate\Auth\Access\AuthorizationException;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $order = $request->input('order') ?? "desc";
        $sortby = $request->input('sort') ?? 'updated_at';
        $linkAppends = ['sort' => $sortby, 'order' => $order];
        $searchColumns = [
            "search_name" => null,
            "search_status" => null,
            "search_request_type" => null,
            "search_authorize_status" => null
        ];

        $query = Company::with(["companyChangeRequest"]);
        if (!empty($request->input('search_name'))) {
            $searchColumns['search_name'] = $request->input('search_name');
            $linkAppends['search_name'] = $request->input('search_name');
            $query->where('name', 'like', '%' . trimSpace($request->input('search_name')) . '%');
        }
        if (!empty($request->input('search_status'))) {
            $searchColumns['search_status'] = $request->input('search_status');
            $linkAppends['search_status'] = $request->input('search_status');
            $query->where('status', $request->input('search_status'));
        }

        $user = Auth::user();
        if (empty($user->admin)) {
            $query->whereIn('id', $user->getCanViewIds('company_id'));
        }

        // 指定したリクエストのステータスを持つもの
        $query->whereHas('companyChangeRequest', function($query) use($request, &$searchColumns, &$linkAppends) {
            if (!empty($request->input('search_request_type'))) {
                $query->where('request_type', $request->input('search_request_type'));
                $searchColumns['search_request_type'] = $request->input('search_request_type');
                $linkAppends['search_request_type'] = $request->input('search_request_type');
            }
            if (!empty($request->input('search_authorize_status'))) {
                $query->where('authorize_status', $request->input('search_authorize_status'));
                $searchColumns['search_authorize_status'] = $request->input('search_authorize_status');
                $linkAppends['search_authorize_status'] = $request->input('search_authorize_status');
            } elseif (empty($request->input('search_request_type')) && empty($request->input('search_authorize_status'))) {
                // 申請理由、審査状況のパラメータなしの時は、公開中も取得
                $query->orWhere('status', Company::STATUS_PUBLISH);
            }
        });

        $query->sortable([$sortby => $order]);
        $list = $query->paginate();
        $links = $list->appends($linkAppends)->links();

        return view('partner.companies.index', [
            'companies' => $list,
            'links' => $links,
            'search' => $searchColumns,
            'statuses' => Company::getEnumColumns('status'),
            'request_types'    => CompanyChangeRequest::getEnumColumns('request_type'),
            'authorize_statuses' => CompanyChangeRequest::getEnumColumns('authorize_status'),
            'user' => Auth::user()
        ]);
    }

    public function info(Company $company)
    {
        return view('partner.companies.info', [
            'company' => $company,
            'statuses' => Company::getEnumColumns('status'),
            'companyfacilities' => Companyfacility::orderBy('order', 'asc')->get()
        ]);
    }

    public function create()
    {
        return $this->base();
    }

    public function base(Company $company=null)
    {
        if ($company) {
            // 管理者じゃないかつ自分の会社じゃない場合はリダイレクト
            $this->authorize('editBase', $company);
        } else {
            $company = null;
        }
        return view('partner.companies.edit.base', [
            'company' => $company,
            'statuses' => Company::getEnumColumns('status'),
            'request_types'    => CompanyChangeRequest::getEnumColumns('request_type'),
            'authorize_statuses' => CompanyChangeRequest::getEnumColumns('authorize_status'),
            'prefectures' => Prefecture::get(),
        ]);
    }

    // 基本情報更新
    public function updateBase(Request $request, Company $company=null)
    {
        if (!is_null($company)) {
            $this->authorize('editBase', $company);
        }
        $request->merge(['city_id' => City::getIdByAddress($request->address1, Prefecture::where("id", $request->prefecture_id)->first())]);
        $params = $request->all();
        $validator = Validator::make($params, [
            'name' => 'required|max:255',
            'description' => 'required|max:10000',
            'post_code' => 'required|digits:7',
            'prefecture_id' => 'required',
            'address1' => 'required|max:255',
            'address2' => 'required|max:255',
            'address_building' => 'nullable|max:255',
            'tel_num' => 'required|digits_between:9,11'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。'])
                ->withErrors($validator->errors())
                ->withInput();
        }

        DB::beginTransaction();
        try {
            if (is_null($company)) {
                $company = new Company;
                // 会社・申請登録イベント
                event(new CompanyRegisterEvent($company->updateBase($params)));
            } else {
                event(new CompanyRequestBaseEvent($company->updateBase($params)));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。お手数ですが、再度お試しください。']);
        }
        UserLog::store($request);
        return redirect()->route('companies.info', [$company->id])
                ->with(['status' => true, 'message' => 'データを更新しました。']);
    }

    // 新規登録申請
    public function applyRegistration(Request $request, Company $company)
    {
        return $this->excuteApply($request, $company, CompanyChangeRequest::OPERATION_REGISTRATION);
    }

    // 重複項目変更申請
    public function applyUpdate(Request $request, Company $company)
    {
        return $this->excuteApply($request, $company, CompanyChangeRequest::OPERATION_UPDATE_APPLY);
    }

    // 申請取りやめ
    public function applyCancel(Request $request, Company $company)
    {
        return $this->excuteApply($request, $company, CompanyChangeRequest::OPERATION_CANCEL);
    }

    // 削除申請
    public function applyDelete(Request $request, Company $company)
    {
        // 削除用の申請を作成
        $company->makeDeleteRequest();
        return $this->excuteApply($request, $company, CompanyChangeRequest::OPERATION_DELETE);
    }

    // 申請系の共通処理
    private function excuteApply($request, $company, $operation)
    {
        $this->authorize($operation, $company);
        DB::beginTransaction();
        try {
            $params = CompanyChangeRequest::OPERATION_CHANGE_PARAMS[$operation];
            if ($operation == CompanyChangeRequest::OPERATION_CANCEL) {
                $company->companyChangeRequest->message = "";
            }
            $company->companyChangeRequest->excuteApply($params, $operation);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with(['status' => false, 'message' => '申請に失敗しました。お手数ですが、再度お手続き下さい。']);
        }
        UserLog::store($request);
        return redirect()->route('companies.info', [$company->id])
                ->with(['status' => true, 'message' => $params['message']])
                ->withInput();
    }

    // 審査開始
    public function judgeProcessing(Request $request, Company $company)
    {
        return $this->excuteJudge($request, $company, CompanyChangeRequest::OPERATION_PROCESSING);
    }

    // 審査承認
    public function judgeAuthorize(Request $request, Company $company)
    {
        return $this->excuteJudge($request, $company, CompanyChangeRequest::OPERATION_AUTHORIZE);
    }

    // 審査却下
    public function judgeReject(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            "message" => "required|max:10000"
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                    ->with(['status' => false, 'message' => '却下処理に失敗しました。入力内容を確認し、再度お試しください。'])
                    ->withErrors($validator->errors())
                    ->withInput();
        }
        return $this->excuteJudge($request, $company, CompanyChangeRequest::OPERATION_REJECT, $request->all());
    }

    // 承認時のバリデーションメッセージ
    private function validateJudgeErrorMessage($request, $operation, $company)
    {
        if ($operation == CompanyChangeRequest::OPERATION_AUTHORIZE) {
            if ($company->companyChangeRequest->request_type != CompanyChangeRequest::REQUEST_TYPE_DELETE) {
                if ($operation == CompanyChangeRequest::OPERATION_AUTHORIZE && $company->notHasCityId()) {
                    return "承認に失敗しました。市区町村が検出できないため市区町村の検索結果に表示できません。住所を見直してください。";
                }
            }
        }
        return false;
    }

    // 承認の共通処理
    private function excuteJudge($request, $company, $operation, $data=null)
    {
        $this->authorize($operation, $company);
        if ($eMessage = $this->validateJudgeErrorMessage($request, $operation, $company)) {
            return redirect()->back()
                ->with(["status" => false, "message" => $eMessage]);
        }
        DB::beginTransaction();
        try {
            $params = CompanyChangeRequest::OPERATION_CHANGE_PARAMS[$operation];
            $company->companyChangeRequest->excuteJudge($params, $operation, $data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->with(['status' => false, 'message' => '申請に失敗しました。お手数ですが、再度お試しください。']);
        }

        UserLog::store($request);

        // 削除の場合のみ、一覧に飛ぶ
        if ($company->companyChangeRequest->request_type == CompanyChangeRequest::REQUEST_TYPE_DELETE && $company->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_AUTHORIZE) {
            return redirect()->route('companies')
                ->with(['status' => true, 'message' => "会社の削除が完了しました。"])
                ->withInput();
        }
        return redirect()->route('companies.info', [$company->id])
                ->with(['status' => true, 'message' => $params["message"]])
                ->withInput();
    }

    // 画像設定
    public function images(Company $company)
    {
        $this->authorize('editBase', $company);

        return view('partner.companies.edit.images', [
            'company' => $company,
        ]);
    }

    // 画像設定フォーム
    public function editImage(Company $company, PartnerImage $image=null)
    {
        $this->authorize('editBase', $company);
        return view('partner.companies.edit.image', [
            'company' => $company,
            'image' => $image,
            'order' => $company->getImageOrder($image)
        ]);
    }

    // 画像追加フォーム
    public function image(Request $request, Company $company)
    {
        $this->authorize("addImage", $company);
        return $this->editImage($company);
    }

    // 画像更新
    public function updateImage(Request $request, Company $company=null, PartnerImage $image=null)
    {
        $this->authorize('updateBase', $company);
        // 新規追加の場合は10個未満かチェックする
        if (empty($image)) {
            if (count($company->currentImages()) >= 10) {
                // 前の画面に戻ると403画面になるため、画像一覧ページに戻す。
                return redirect()->route("companies.images", [$company->id])
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。画像は10個までしか設定出来ません。']);
            }
        }
        $validator = Validator::make($request->all(), [
            "image_alt" => "required|max:100",
            // sometime required
            "image_filename" => "image|max:5000000|dimensions:min_width=800,min_height=600,
"
        ]);
        $validator->sometimes('image_filename', 'required', function($input) use($image) {
            return empty($image) || empty($image->filepath);
        });
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。'])
                ->withErrors($validator->errors())
                ->withInput();
        }

        if ($request->has('image_filename')) {
            self::coverImageConvert($request->image_filename->getPathname());
        }

        DB::beginTransaction();
        $newImage = new PartnerImage;
        try {
            if ($request->has('image_filename')) {
                $newImage->storeCompany($request->image_filename);
                $newImage->company_id = $company->id;
            // altタグのみの更新の場合
            } else {
                $newImage->filepath = $image->filepath;
            }
            $newImage->alt = $request->image_alt;
            $newImage->save();
            $company->updateImage($newImage, $image);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。お手数ですが、再度お試しください。']);
        }
        $params = $request->except("image_filename");
        $params["image_filename"] = $newImage->filepath;
        UserLog::store($request, $params);
        return redirect()->route('companies.images', [$company->id])
                ->with(['status' => true, 'message' => 'データの更新に成功しました。'])
                ->withInput();
    }

    // 削除
    public function destroyImage(Request $request, Company $company, PartnerImage $image)
    {
        $this->authorize('updateBase', $company);
        DB::beginTransaction();
        try {
            $company->destroyImage($image);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。お手数ですが、再度お試しください。']);
        }
        UserLog::store($request);
        return redirect()->route('companies.images', [$company->id])->with(['status' => true, 'message' => 'データの削除が完了しました。']);
    }

    // 画像並び替え
    public function imagesOrder(Company $company)
    {
        $this->authorize('updateBase', $company);
        return view('partner.companies.edit.images_order', ['company' => $company, 'isMobile' => Agent::isMobile() ?? false]);
    }

    // 画像並び替え更新
    public function updateImagesOrder(Request $request, Company $company)
    {
        $list = $request->input('selected');
        DB::beginTransaction();
        try {
            $company->updateImagesOrder($list, $company->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with(['status' => false, 'message' => '表示順の保存に失敗しました。再度お試しください。']);
        }
        UserLog::store($request);
        return redirect()
            ->route('companies.images', [$company->id])
            ->with(['message' => '表示順を保存しました。']);
    }

    // 詳細情報１
    public function detail1(Company $company)
    {
        return view('partner.companies.edit.detail1', [
            'company' => $company,
        ]);
    }

    // 詳細情報１更新
    public function updateDetail1(Request $request, Company $company)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'holiday' => 'max:10000',
            'url' => 'nullable|max:10000|url',
            'twitter' => 'nullable|max:255',
            'facebook' => 'nullable|max:255',
            'instagram' => 'nullable|max:255',
            'company_name' => 'nullable|max:255',
            'buisines_hours' => 'nullable|max:10000',
            'admission_fee' => 'nullable|string|max:10000',
            'base_fee' => 'nullable|max:10000',
            'rental_description' => 'nullable|max:10000',
            'trial_description' => 'nullable|max:10000'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。'])
                ->withErrors($validator->errors())
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $company->updateDetail1($params);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。お手数ですが、再度お試しください。']);
        }
        UserLog::store($request);
        return redirect()->route('companies.info', [$company->id, "#detail"])
                ->with(['status' => true, 'message' => 'データを更新しました。'])
                ->withInput();
    }

    // 詳細情報2
    public function detail2(Request $request, Company $company)
    {
        $company->load(['stations' => function($query) {
            $query->with(['railroad']);
        }]);

        $railroads = Railroad::select("id", "name", "railroad_company_id")
                        ->with(['stations:id,name,group_code,railroad_id', 'company'])
                        ->whereHas('stations', function($query) use($company) {
                            $query->where('prefecture_id', $company->prefecture_id);
                        })
                        ->orderBy('railroad_company_id', 'asc')
                        ->orderBy('sort', 'asc')
                        ->orderBy('code', 'asc')
                        ->get();

        return view('partner.companies.edit.detail2', [
            'company' => $company,
            'railroads' => $railroads,
        ]);
    }

    // 詳細情報2アップデート
    public function updateDetail2(Request $request, Company $company=null)
    {
        $validator = Validator::make($request->all(), [
            'address_description' => 'nullable|max:10000',
            'stations' => 'max_count:3'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。入力値を確認してください'])
                ->withErrors($validator->errors())
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $company->updateDetail2($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()
                ->with(['status' => false, 'message' => 'データの更新に失敗しました。お手数ですが、再度お試しください。']);
        }
        UserLog::store($request);
        return redirect()->route('companies.info', [$company->id, "#access"])
                ->with(['status' => true, 'message' => 'データを更新しました。'])
                ->withInput();
    }

    // 検索条件
    public function search(Company $company)
    {
        return view('partner.companies.edit.search', [
            'company' => $company,
            'companyfacilities' => Companyfacility::orderBy('order', 'asc')->get()
        ]);
    }

    // 会社編集メンバー
    public function users(Request $request, Company $company)
    {
        $list = UsersCompany::where('company_id', $company->id)->get()->filter(function($record) {
            return empty($record->user->admin_id);  // 管理者以外のレコードを抽出
        });
        return view('partner.companies.users', ['company' => $company, 'list' => $list]);
    }

    // 会社編集メンバー削除
    public function removeUser(Request $request, Company $company)
    {
        $uid = $request->input('btn-remove');
        if (empty($uid)) {
            return back()->with(['status' => false, 'message' => 'データが見つかりませんでした。お手数ですが、再度お試しください。']);
        }

        $members = UsersCompany::where('company_id', $company->id)->get();
        $target = $members->where('user_id', $uid)->first();
        if (empty($target)) {
            return back()->with(['status' => false, 'message' => 'データが見つかりませんでした。お手数ですが、再度お試しください。']);
        }

        $owners = $members->where('is_owner', true)->filter(function($record) {
            return (!$record->user->admin_id && $record->user->isStatusMember()); // 会員登録済のオーナーに絞る
        });
        if ($owners->count() === 1 && $target->is_owner && $target->user->isStatusMember()) {
            return back()->with(['status' => false, 'message' => '管理者を１人以上残す必要があるため、削除できません。']);
        }

        $target->deleted_at = date_create();
        $target->save();

        UserLog::store($request);

        return redirect()
            ->route("companies.info", [$company->id, "#company-member"])
            ->with(['message' => "メンバーを削除しました。"]);
    }

    // 会社編集メンバー招待
    public function inviteUser(Request $request, Company $company)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                'max:100',
                'banned_email',
                'duplicated_member:' . $company->id
            ],
        ]);
        $email = $request->input('email');

        DB::beginTransaction();
        try {
            $user = User::firstOrNew(['email' => $email]);
            if (empty($user->provisional_registered_at)) {
                $user->status = User::STATUS_TYPE_INVITE;
                $user->save();
            }

            $users_companies = UsersCompany::withTrashed()->firstOrNew(['user_id' => $user->id, 'company_id' => $company->id]);
            $users_companies->is_owner = true;
            $users_companies->deleted_at = null;
            $users_companies->save();

            $users_companies->inviteCompanyMemberNotification(); // 招待メール送信
            DB::commit();
            UserLog::store($request);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return back()->with(['status' => false, 'message' => '招待メールの送信に失敗しました。お手数ですが、入力されたメールアドレスが正しく存在するかを確認した上で、再度やり直して下さい。']);
        }

        return redirect()
            ->route("companies.info", [$company->id, "#company-member"])
            ->with(['message' => '招待メールを送信しました。']);
    }

}
