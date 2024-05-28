<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Permission;
use App\Models\User;
use App\Models\Report;

class ReportPolicy
{
    /**
     * Create a new policy instance.
     */


    public function list_reports_sidebar(User $user) {
        return $user->is_report_user() ||
            Permission::where('user_id',$user->id)->whereIn('permission',[
                'view_reports',
                'manage_reports',
                'run_reports'
            ])->first();
    }
    public function list_search_reports(User $user){
        return $user->is_report_user() ||
            Permission::where('user_id',$user->id)
            ->where(function($q){
                $q->orWhere('permission','view_reports')
                    ->orWhere('permission','manage_reports');
            })
            ->first();
    }

    public function manage_reports(User $user){
        return !is_null(Permission::where('user_id',$user->id)
            ->where('permission','manage_reports')->first());
    }

    public function run_reports(User $user, Report $report){
        return !is_null(Permission::where('user_id',$user->id)->where('permission','run_reports')->first()) ||
            !is_null(Report::where('owner_user_id',$user->id)->where('id',$report->id)
                ->first());
    }

}
