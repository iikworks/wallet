<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Plus extends Component
{
    private string $link;

    /**
     * Create a new component instance.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.plus', [
            'link' => $this->link,
        ]);
    }
}
