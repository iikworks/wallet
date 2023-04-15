<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Blue extends Component
{
    private string $title;
    private string $classes;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title, string $classes = '')
    {
        $this->title = $title;
        $this->classes = $classes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.blue', [
            'title' => $this->title,
            'classes' => $this->classes,
        ]);
    }
}
