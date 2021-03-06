<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function handle_facebook_redirect(){
        return Socialite::driver('facebook')->redirect();
    }

    public function handle_facebook_callback(){
        $user = Socialite::driver('facebook')->stateless()->user();
        // dd($user->email);
        $user = User::firstOrCreate([
            'email' => $user->email
        ],[
            'name' => $user->name,
            'email' => $user->email,
            'password' => "12345678",
        ]);
        Auth::login($user);
        return redirect()->route('home');
    }

}
