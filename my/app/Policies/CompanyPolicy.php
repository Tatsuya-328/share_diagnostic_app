<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyChangeRequest;
use App\Models\UsersCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
    * @申請・審査周りの権限
    *
    */

    // 会社のオーナー権限のチェック
    public function own($user, $company)
    {
        if (!empty($user->admin)) {
            return true;
        }
        return $user->isCompanyOwner($company->id) === true;
    }

    // 権限ごとに、ステータス別に可能かを見る
    private function operationPermitStatus($user, $company, $operation, $list=null)
    {
        if (empty($list)) {
            $list = CompanyChangeRequest::OPERATION_PERMISSION_PARAMS[$operation];
        }
        $requestTypes = $list["request_types"];
        $authorizeStatuses = $list["authorize_statuses"];
        if (!in_array($company->companyChangeRequest->request_type, $requestTypes) || !in_array($company->companyChangeRequest->authorize_status, $authorizeStatuses)) {
            return false;
        }
        return true;
    }

    // 登録申請権限
    public function registration(User $user, Company $company)
    {
        if ($company->status != Company::STATUS_DISABLE) {
            return false;
        }
        return (!empty($company->companyChangeRequest) && $this->operationPermitStatus($user, $company, CompanyChangeRequest::OPERATION_REGISTRATION));
    }

    // 重要項目変更申請権限
    public function updateApply(User $user, Company $company)
    {
        if ($company->status != Company::STATUS_PUBLISH) {
            return false;
        }
        if (empty($company->companyChangeRequest) || !$this->operationPermitStatus($user, $company, CompanyChangeRequest::OPERATION_UPDATE_APPLY)) {
            return false;
        }
        // 公開後に新たにリクエストがあればOK
        return $company->isRequestChanged();
    }

    // 削除申請権限
    public function delete(User $user, Company $company)
    {
        if ($company->status == Company::STATUS_DISABLE) {
            return false;
        }

        if (!empty($company->companyChangeRequest)) {
            // 申請ステータスありの場合
            if ($company->companyChangeRequest->request_type == CompanyChangeRequest::REQUEST_TYPE_DELETE || $company->companyChangeRequest->request_type == CompanyChangeRequest::REQUEST_TYPE_UPDATE) {
                // 却下以外の場合
                if ($company->companyChangeRequest->authorize_status != CompanyChangeRequest::AUTHORIZE_STATUS_REJECT) {
                    return false;
                }
            }
        }
        return true;
    }

    // 申請取り消し権限
    public function cancel(User $user, Company $company)
    {
        return (!empty($company->companyChangeRequest) && $this->operationPermitStatus($user, $company, CompanyChangeRequest::OPERATION_CANCEL));
    }

     // 審査権限
    public function processing(User $user, Company $company)
    {
        if (empty($user->admin)) {
            return false;
        }
        return (!empty($company->companyChangeRequest) && $this->operationPermitStatus($user, $company, CompanyChangeRequest::OPERATION_PROCESSING));
    }

    // 承認権限
    public function authorize(User $user, Company $company)
    {
        if (empty($user->admin)) {
            return false;
        }
        if (empty($user->admin_id) || empty($company->companyChangeRequest) || $company->companyChangeRequest->admin_id != $user->admin_id) {
            return false;
        }
        return $this->operationPermitStatus($user, $company, CompanyChangeRequest::OPERATION_AUTHORIZE);
    }

    // 却下権限
    public function reject(User $user, Company $company)
    {
        if (empty($user->admin)) {
            return false;
        }
        return $this->authorize($user, $company);
    }


    /**
    * @閲覧・編集周りの権限
    *
    */
    // 閲覧権限
    public function view(User $user, Company $company)
    {
        if (!empty($user->admin)) {
            return true;
        }
        $userIds = $company->usersCompanies->pluck('user_id')->toArray();
        return in_array($user->id, $userIds) ?: false;
    }

    public function edit(User $user, Company $company)
    {
        // 現時点ではtrue
        return true;
    }

    // 基本情報編集権限
    public function editBase(User $user, Company $company)
    {
        // リクエストがある場合は、条件によって弾く
        if (!empty($company->companyChangeRequest)) {
            if (isAdmin()) {
                return true;
            }
            if ($company->companyChangeRequest->request_type == CompanyChangeRequest::REQUEST_TYPE_NONE) {
                return true;
            }
            // 公開中の権限
            if ($company->status == Company::STATUS_PUBLISH) {
                if (!$this->operationPermitStatus($user, $company, "edit", CompanyChangeRequest::OPERATION_PERMISSION_PARAMS["edit"][Company::STATUS_PUBLISH], true)) {
                    return false;
                }
            // 公開前の権限
            } else {
                if (!$this->operationPermitStatus($user, $company, "edit", CompanyChangeRequest::OPERATION_PERMISSION_PARAMS["edit"][Company::STATUS_DISABLE], true)) {
                    return false;
                }
            }
        }
        return true;
    }

    // 基本情報更新権限
    public function updateBase(User $user, Company $company)
    {
        // 編集権限と同じ
        return $this->editBase($user, $company);
    }

    // 画像追加権限
    public function addImage(User $user, Company $company)
    {
        // 更新は画像権限と同じ
        if (!$this->editBase($user, $company)) {
            return false;
        }
        // 新規追加の場合は、10個未満の場合しか許可しない
        return count($company->currentImages()) < 10;
    }

}
