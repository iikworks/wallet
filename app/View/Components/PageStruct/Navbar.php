<?php

namespace App\View\Components\PageStruct;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    private User $user;

    public function __construct()
    {
        $this->user = request()->user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-struct.navbar', [
            'user' => $this->user,
        ]);
    }
}
