<?php

namespace Palamike\Foundation\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Palamike\Foundation\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $linkRequestView = 'foundation::auth.passwords.email';
    protected $resetView = 'foundation::auth.passwords.reset';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = setting('login.redirect');
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        $role = Auth::user()->roles->first();
        return redirect(!empty($role->redirect) ? $role->redirect : $this->redirectPath())->with('status', trans($response));
    }
}
