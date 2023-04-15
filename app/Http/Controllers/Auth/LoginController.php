<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateUserByCredentialsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\UnauthorizedException;

class LoginController extends Controller
{
    public function form(): View|Application|Factory
    {
        return view('auth.login', [
            'title' => __('auth.login'),
        ]);
    }

    public function login(LoginRequest $request, AuthenticateUserByCredentialsAction $authenticateUserAction): RedirectResponse
    {
        try {
            ($authenticateUserAction)($request->validated(), true);
            return redirect()->route('dashboard');
        } catch (UnauthorizedException) {
            return back()->withErrors(['phone' => __('auth.failed')]);
        }
    }
}
