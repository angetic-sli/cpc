<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; use App\Models\User; use App\Models\Role; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use Illuminate\Support\Facades\Hash; use Illuminate\Validation\Rules;
class RegisteredUserController extends Controller {
 public function create(){return view('auth.register');}
 public function store(Request $request){
  $data=$request->validate(['name'=>'required|string|max:150','email'=>'required|string|lowercase|email|max:190|unique:users','phone'=>'nullable|string|max:40','password'=>['required','confirmed',Rules\Password::defaults()]]);
  $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'phone'=>$data['phone']??null,'password'=>Hash::make($data['password'])]);
  $role=Role::where('name','client')->first();if($role)$user->roles()->syncWithoutDetaching([$role->id]);
  Auth::login($user);return redirect()->route('account');
 }
}
