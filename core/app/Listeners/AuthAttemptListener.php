<?php

namespace App\Listeners;

use Log;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;

class AuthAttemptListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($credentials, $remember, $login)
    {
        //
        Log::info("User is attempting with " . $credentials['email']);
        Log::info("User is attempting with " . $credentials['password']);
        // If this user only has an imported_password set, then set their
        // regular password if the login is correct for the imported_password.
        $user = User::where('email', $credentials['email'])->first();
        if (is_null($user)) return;

        if (!is_null($user->imported_password) 
            && $user->imported_password == md5($credentials['password'])) {
            $user->password = bcrypt($credentials['password']);
            $user->imported_password = null;
            $user->save();
        }
    }
}
