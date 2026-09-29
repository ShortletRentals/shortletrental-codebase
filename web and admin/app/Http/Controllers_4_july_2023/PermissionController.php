<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App;
use DB;

class PermissionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

  public function index(){
    Gate::authorize('Permissions-section');
    // dd('dasd');
     $data['permissions']=Permission::where('is_show', 'Yes')->orderBy('position','asc')->get()->toArray();
    //  $data['roles']=Role::all();
     $data['roles']=$this->getRoles();
    //  dd($data['roles']);
     $locale = App::getLocale();

    $login_user_data = auth()->user();
     return view('admin.permissions.permissions',$data);
  }

    public function getRoles()
    {
        Gate::authorize('Permissions-section');
        // $role=Role::all();
        $role=Role::whereIn('id', [2,1])->get();
        foreach ($role as $key=>$rol) {
            $permissions = $rol->permissions->pluck('id')->toArray();
            $role[$key]['permission_ids']=$permissions;
            // dd($permissions);
        }
        return $role->toArray();
    }

    
     public function user_permissions($userid=''){
        // dd('in');
      Gate::authorize('Permissions-section');  
      // Gate::authorize('Permission-user');
    //   dd($userid);
       $user = User::findOrFail($userid);
    //    dd($user->user_type);
        if($user->user_type == 5){
            $data['permissions']=Permission::where(['is_show'=> 'Yes', 'host_show'=>1])->orderBy('position','asc')->get()->toArray();
        } else if ($user->user_type == 3) {
            $data['permissions']=Permission::where(['is_show'=> 'Yes', 'influencer_show'=>1])->orderBy('position','asc')->get()->toArray();
        } else if ($user->user_type == 2) {
            $data['permissions']=Permission::where(['is_show'=> 'Yes', 'sub_admin_show'=>1])->orderBy('position','asc')->get()->toArray();
        } else {
            $data['permissions']=Permission::where('is_show', 'Yes')->orderBy('position','asc')->get()->toArray();
        }
       
       $data['user_permission']=$user->getAllPermissions()->pluck('id')->toArray();
       $data['user']=$user;
       // $data['user_permission']=$user->getAllPermissions()->pluck('id')->toArray();
       // if($userid ==1)
       //  {
       //      return redirect('permissions');
       //  }
		$login_user_data = auth()->user();
        $json = json_encode(array('userid'=>$userid));
        if($userid == '')
        {
            return redirect('permissions');
        }
        if(!User::find($userid))
        {
            return redirect('permissions');
        }
       return view('admin.permissions.user_permissions',$data);
    }  
   
   /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function perm_userData()
    {
        Gate::authorize('Permissions-section');
       // $user=User::select('email','name','id')->where('id', '!=', 1);
       $user=User::select('email','name','type','id')->whereIn('type', [2,8,7]);
        return Datatables::of($user)
         ->addColumn('action', function ($user) {
                return '<a href="'.url('permissions/user_permissions/'.$user->id).'"  title="Add Permissions" class="btn btn-xs btn-primary add_permissions"><i class="fa fa-edit"></i>Permissions</a>
                ';
            }) ->make(true);
    }


    public function saveRolePermission($role_id,$permission_id){
        Gate::authorize('Permissions-section');
        // Gate::authorize('Permission-role');

        $role = Role::findOrFail($role_id);
        if($role->givePermissionTo($permission_id)){
         $result=array(
             'status'=>true,
             'message'=>__('Access given to ').$role->name
         );
        }
        else{
            $result=array(
                'status'=>false,
                'message'=>__('Something went wrong.')
            );
        }
        return response()->json($result);
    }

    public function deleteRolePermission($role_id,$permission_id){
        Gate::authorize('Permissions-section');
        // Gate::authorize('Permission-role');
        $role = Role::findOrFail($role_id);
        if($role->revokePermissionTo($permission_id)){
         $result=array(
             'status'=>true,
             'message'=>__('Access revoked to ').$role->name
         );
        }
        else{
            $result=array(
                'status'=>false,
                'message'=>__('Something went wrong.')
            );
        }
        return response()->json($result);
    }

    public function saveUserPermission($user_id,$permission_id){
        Gate::authorize('Permissions-section');
        // Gate::authorize('Permission-user');
        $user = User::findOrFail($user_id);
        if($user->givePermissionTo($permission_id)){
            $result=array(
                'status'=>true,
                'message'=>__('Access given to ').$user->name
            );
        }
        else{
            $result=array(
                'status'=>false,
                'message'=>__('Something went wrong.')
            );
        }
        return response()->json($result);
    }

    public function deleteUserPermission($user_id,$permission_id){
        Gate::authorize('Permissions-section');
        // Gate::authorize('Permission-user');
        $user = User::findOrFail($user_id);
        if($user->revokePermissionTo($permission_id)){
         $result=array(
             'status'=>true,
             'message'=>__('Access revoked to ').$user->name
         );
        }
        else{
            $result=array(
                'status'=>false,
                'message'=>__('Something went wrong.')
            );
        }
        return response()->json($result);
    }
}
