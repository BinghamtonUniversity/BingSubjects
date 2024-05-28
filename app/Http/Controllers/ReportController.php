<?php

namespace App\Http\Controllers;

use App\Models\Report;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Libraries\QueryBuilder;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $tables = ['studies','participants','study_participants','study_data_types','data_types'];
    public function __construct() {}

    public function get_all_reports(Request $request) {
        $user = Auth::user();

        if($user->can('manage_reports','App\Report')){
            return Report::all();
        }else{
            return Report::where(function($q) use ($user){
                $q->where('owner_user_id',$user->id)
                    ->orWhereJsonContains('permissions',$user->id);
            })->get();
        }
    }

    public function get_report(Request $request, Report $report) {
        return $report;
    }

    public function add_report(Request $request) {
        $report = new Report($request->all());
        $report->save();
        return $report->where('id',$report->id)->with('owner')->first();
    }

    public function update_report(Request $request, Report $report) {
        $report->update($request->all());
        return $report->where('id',$report->id)->with('owner')->first();
    }

    public function delete_report(Request $request, Report $report) {
        $report->delete();
        return 'Success';
    }

    public function get_columns(Request $request) {
        $columns = [];
        foreach($this->tables as $table) {
            $table_columns = Schema::getColumnListing($table);
            foreach($table_columns as $column) {
                if (!in_array($column,['id','created_at','updated_at'])) {
                    $columns[] = $table.'.'.$column;
                }
            }
        }
        return $columns;
    }

    public function get_tables(Request $request) {
        return $this->tables;
    }
    public function execute(Request $request, Report $report) {

        $default_columns = [
            'email'
        ];
        $columns = array_merge($default_columns, $report->report->columns);

//        $subq_user_groups = DB::table('participants')
//            ->leftJoin('study_participants', function($join) {
//                $join->on('participants.id','=','study_participants.participant_id');
//            })->groupBy('participants.id')
//            ->select('group_memberships.user_id', DB::raw('group_concat(`groups`.`name`) as `groups`'));
        $q = DB::table('participants')
            ->leftJoin('study_participants', function ($join) {
                $join->on('participants.id', '=', 'study_participants.participant_id');
            // })->leftJoin('study_data_types', function ($join) {
            //     $join->on('study_participants.study_id', '=', 'study_data_types.study_id');
            })->leftJoin('studies', function ($join) {
                $join->on('study_participants.study_id', '=', 'studies.id');
            })
            // ->leftJoin('data_types', function ($join) {
            //     $join->on('study_data_types.data_type_id', '=', 'data_types.id');
            // })
//            ->leftJoin('group_memberships', function ($join) {
//                $join->on('users.id', '=', 'group_memberships.user_id');
//            })->leftJoin('groups', function ($join) {
//                $join->on('group_memberships.group_id', '=', 'groups.id');
//            })
            // Join with list of user groups
//            ->leftJoinSub($subq_user_groups, 'user_groups', function ($join) {
//                $join->on('users.id', '=', 'user_groups.user_id');
//            })
            ->distinct();
        QueryBuilder::build_where($q, $report->report);

        $results = $q->select($columns)->get();
        return $results;
    }
}
