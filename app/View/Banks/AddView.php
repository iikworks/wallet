<?php

namespace App\View\Banks;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AddView
{
    public function __invoke(): View|Application|Factory
    {
        $action = route('banks');

        return view('banks.add', [
            'title' => __('banks.adding'),
            'bank' => null,
            'action' => $action,
        ]);
    }
}
