<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;use App\Models\User;use App\Models\Role;use Illuminate\Http\Request;
class UserController extends Controller {
 public function index(){ $users=User::with('roles')->latest()->paginate(25);$roles=Role::all();return view('admin.users.index',compact('users','roles')); }
 public function updateRole(Request $r,User $user){$data=$r->validate(['role_id'=>'required|exists:roles,id']);abort_if($user->id===$r->user()->id,422,'Vous ne pouvez pas modifier votre propre rôle.');$user->roles()->sync([$data['role_id']]);return back()->with('success','Rôle mis à jour.');}
}
