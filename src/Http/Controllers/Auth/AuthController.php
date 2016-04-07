<?php

namespace Palamike\Foundation\Http\Controllers\Auth;

use Palamike\Foundation\Models\Auth\SignonSession;
use Palamike\Foundation\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Validator;
use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    protected $username = 'email';
    protected $loginView = 'foundation::auth.login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->username = setting('login.using');
        $this->redirectTo = setting('login.redirect');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);
        $credentials['status'] = 'active';

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was authenticated.
     * If setting login.strategic is single (Single Signon)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }

        $this->handleSingleSignon();

        $role = Auth::user()->roles->first();
        return redirect(!empty($role->redirect) ? $role->redirect : $this->redirectTo);

        //return redirect()->intended($this->redirectPath());
    }

    protected function handleSingleSignon(){
        if(setting('login.strategic') == 'single'){
            $userId = Auth::guard($this->getGuard())->user()->id;

            $signon = SignonSession::user($userId)->first();
            if(empty($signon)){
                $signon = SignonSession::create([
                    'user_id' => $userId,
                    'session_id' => Session::getId()
                ]);
            }
            else if(empty($signon->session_id)){
                $signon->session_id = Session::getId();
                $signon->save();
            }//else

            if(Session::getId() != $signon->session_id){
                Session::getHandler()->destroy($signon->session_id);
                $signon->session_id = Session::getId();
                $signon->save();
            }//if
        }//if
    }//protected function handleSingleSignon()

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {

        $data = $request->all();
        $validator = Validator::make($data, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ],[
            'username.required' => trans('foundation::login.validation.username.required'),
            'email.required' => trans('foundation::login.validation.email.required'),
            'password.required' => trans('foundation::login.validation.password.required'),
        ]);

        $validator->after(function($validator) use ($data){

            $user = User::where($this->loginUsername(),'=',$data[$this->loginUsername()])->first();

            if(empty($user) || ($user->status == 'inactive')){
                $validator->errors()->add($this->loginUsername(), $this->getFailedLoginMessage());
            }//if
        });

        $this->validateWith($validator,$request);
    }

    public function logout()
    {
        $user = Auth::guard($this->getGuard())->user();

        if(!empty($user)){
            $signon = SignonSession::user($user->id)->first();
            if(!empty($signon)){
                $signon->session_id = null;
                $signon->save();
            }//if
        }//if



        Auth::guard($this->getGuard())->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('foundation::login.validation.fail')
            ? Lang::get('foundation::login.validation.fail')
            : 'These credentials do not match our records.';
    }
}
