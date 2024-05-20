<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    public function get_users(Request $request) {
        // If User doesn't have permission to view all users, then only return their own user id
        return User::get();
    }

    public function get_user(Request $request, User $user) {
        return $user;
    }

    public function create_user(Request $request) {
        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function update_user(Request $request, User $user) {
        $user->update($request->all());
        return $user;
    }

    public function delete_user(Request $request, User $user) {
        return $user->delete();
    }

    public function search($search_string='') {
        $search_elements_parsed = preg_split('/[\s,]+/',strtolower($search_string));
        $search = []; $users = []; $ids = collect();
        if (count($search_elements_parsed) === 1 && $search_elements_parsed[0]!='') {
            $search[0] = $search_elements_parsed[0];
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('id',$search[0])->limit(15)->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('first_name','like',$search[0].'%')
                ->orWhere('last_name','like',$search[0].'%')->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('email',$search[0])->limit(15)->get()->pluck('id'));

            $users = User::select('id','first_name','last_name','email')
                ->whereIn('id',$ids)->orderBy('first_name', 'asc')->orderBy('last_name', 'asc')
                ->limit(15)->get()->toArray();
        } else if (count($search_elements_parsed) > 1) {
            $search[0] = $search_elements_parsed[0];
            $search[1] = $search_elements_parsed[count($search_elements_parsed)-1];
            $ids = $ids->merge(DB::table('users')->select('id')
                ->where('first_name','like',$search[0].'%')->where('last_name','like',$search[1].'%')
                ->limit(15)->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->where('first_name','like',$search[1].'%')->where('last_name','like',$search[0].'%')
                ->limit(15)->get()->pluck('id'));
            $users = User::select('id','first_name','last_name','email')
                ->whereIn('id',$ids)->orderBy('first_name', 'asc')->orderBy('last_name', 'asc')
                ->limit(15)->get()->toArray();
        }
        foreach($users as $index => $user) {
            $users[$index] = array_intersect_key($user, array_flip(['id','first_name','last_name','email']));
        }
        return $users;
    }

    /* START User Permissions Methods */
    public function set_permissions(Request $request, User $user) {

        $request->validate([
            'permissions' => 'array',
        ]);
        Permission::where('user_id',$user->id)->delete();

        foreach($request->permissions as $permission) {
            $permission = new Permission([
                'user_id' => $user->id,
                'permission' => $permission
            ]);
            $permission->save();
        }
        return $request->permissions;
    }

    public function get_permissions(Request $request, User $user) {
        return $user->user_permissions;
    }
    /* END User Permissions Methods */
}
