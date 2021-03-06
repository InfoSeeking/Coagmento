<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    protected $redirectPath = '/workspace';
    protected $redirectTo = '/workspace';
    protected $redirectAfterLogout = 'http://coagmento.org';
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
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Called when the user is authenticated
     */
    protected function authenticated(Request $req, User $user) {
        if ($req->has('after_login_redirect')) {
            return redirect($req->input('after_login_redirect'));
        } else {
            return redirect($this->redirectPath());
        }
    }

    public function postLoginWithOldCoagmentoSupport(Request $req) {
        // Check if the email provided is an old Coagmento username.
        $email = $req->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // All imported old Coagmento users are assigned to
            // a placeholder @coagmento.org email address for consistency.
            $email .= '@coagmento.org';
            $req->merge(['email' => $email]);
        }
        // Proceed with standard login.
        return $this->postLogin($req);
    }

    public function demoLogin(Request $req) {
        $demoEmail = 'coagmento_demo@demo.demo';
        $demoUser = User::where('email', $demoEmail)->first();
        if (is_null($demoUser)) {
            $demoUser = $this->create([
                'name' => 'Coagmento Demo',
                'email' => $demoEmail,
                'password' => 'demo'
                ]);
        }
        Auth::login($demoUser, true);
        return $this->authenticated($req, $demoUser);
    }
}
