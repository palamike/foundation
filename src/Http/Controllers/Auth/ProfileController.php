<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 3/10/2016 AD
 * Time: 11:07 AM
 */

namespace Palamike\Foundation\Http\Controllers\Auth;


use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller {

    public $rules = [
        'name' => 'required|max:255',
        'username' => 'required|max:255|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'sometimes|min:8|max:255|confirmed'
    ];

    protected $permissions = [
        'index' => 'user_profile_view',
        'update' => 'user_profile_edit',
    ];

    public function __construct()
    {
        $this->checkPermission();
    }

    public function index(){
        return view('auth.user.profile',['permissions' => $this->mapPermissions()]);
    }

    public function edit(Request $request){
        return user()->load('avatar');
    }

    public function update(Request $request){

        $user = user();

        $this->rules['username'] .= ','.$user->id;
        $this->rules['email'] .= ','.$user->id;

        $this->validate($request,$this->rules,$this->getValidationMessages('user.user'));

        $response = [];

        try{
            $this->beginTransactions();

            $data = $request->all();

            if($request->has('password')){
                $data['password'] = Hash::make($data['password']);
            }//if
            else{
                unset($data['password']);
            }

            $this->stamp($data);

            $user->update($data);
            $response['message'] = trans('user.profile.success.update');

            $this->commit();

            $response['info'] = $user;
            return response()->json($response);
        }//try
        catch(\Exception $e){

            $this->rollBack();

            $message = trans('user.profile.fail.update',['id' => $user->id ]);

            Log::error($message);
            Log::error($e);
            return response($message,409);
        }//catch
    }
}