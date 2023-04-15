<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function logout(LogoutUserAction $action): RedirectResponse
    {
        ($action)();
        return redirect()->route('login');
    }
}
