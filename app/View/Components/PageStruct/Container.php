<?php

namespace App\View\Components\PageStruct;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Container extends Component
{
    private string $classes;

    /**
     * Create a new component instance.
     */
    public function __construct(string $classes = "")
    {
        $this->classes = $classes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-struct.container', [
            "classes" => $this->classes,
        ]);
    }
}
