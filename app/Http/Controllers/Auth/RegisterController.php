<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Users\StoreUserAndAuthenticateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RegisterController extends Controller
{
    public function form(): View|Application|Factory
    {
        return view('auth.register', [
            'title' => __('auth.register'),
        ]);
    }

    public function store(RegisterRequest $request, StoreUserAndAuthenticateAction $storeUserAction): RedirectResponse
    {
        try {
            ($storeUserAction)($request->validated(), true);
            return redirect()->route('dashboard');
        } catch (UnprocessableEntityHttpException $e) {
            return back()->withErrors(['phone' => $e->getMessage()]);
        }
    }
}
