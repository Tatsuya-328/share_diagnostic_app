<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyChangeRequest;

class HomeController extends Controller
{
    public function index()
    {
        $params = [];
        $company_collection = CompanyChangeRequest::with('company')->get();
        $params['unprocessed_signup_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_SIGNUP)
            ->count();
        $params['unprocessed_update_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_UPDATE)
            ->count();
        $params['unprocessed_delete_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_UNPROCESS)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_DELETE)
            ->count();
        $params['processing_signup_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_PROCESSING)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_SIGNUP)
            ->count();
        $params['processing_update_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_PROCESSING)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_UPDATE)
            ->count();
        $params['processing_delete_badge'] = $company_collection
            ->where('authorize_status', CompanyChangeRequest::AUTHORIZE_STATUS_PROCESSING)
            ->where('request_type', CompanyChangeRequest::REQUEST_TYPE_DELETE)
            ->count();

        return view('partner.homes.index', $params);
    }
}
