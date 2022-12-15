<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CompanyChangeRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyChangeRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    // 閲覧、編集などの操作の基本権限
    private function actionGeneral(User $user, CompanyChangeRequest $company)
    {
        // 管理者もしくは、会社作成者のみ可能
        return !empty($company) && (!empty($user->admin) || ($user->id == $company->user_id));
    }

    public function view(User $user, CompanyChangeRequest $company)
    {
        return $this->actionGeneral($user, $company);
    }

    public function edit(User $user, CompanyChangeRequest $company)
    {
        return $this->actionGeneral($user, $company);
    }

    public function update(User $user, CompanyChangeRequest $company)
    {
        return $this->actionGeneral($user, $company);
    }

}