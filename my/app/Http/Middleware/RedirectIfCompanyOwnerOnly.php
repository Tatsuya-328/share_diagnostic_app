<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfCompanyOwnerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company = $request->route()->parameter('company');
        $user = Auth::user();

        if (empty($user->admin_id) && $user->isCompanyOwner($company->id) === false) {
            return redirect('companies')->with(['status' => false, 'message' => '閲覧権限がありません']);
        }

        return $next($request);
    }
}
