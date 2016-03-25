<?php
namespace App\Traits;

use Auth;
use Illuminate\Http\Request;

use App\Models\User;

trait AuthenticateCoagmentoUsers {
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
        return redirect($this->redirectPath);
    }
}