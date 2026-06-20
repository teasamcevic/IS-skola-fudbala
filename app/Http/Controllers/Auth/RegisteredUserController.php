<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
class RegisteredUserController extends Controller
{
    public function create()
    {
        return redirect()->away(rtrim(config('app.frontend_url'), '/').'/register');
    }

}
