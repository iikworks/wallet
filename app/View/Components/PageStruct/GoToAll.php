<?php

namespace App\View\Components\PageStruct;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GoToAll extends Component
{
    private string $link;
    private string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(string $link, string $title)
    {
        $this->link = $link;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-struct.go-to-all', [
            'link' => $this->link,
            'title' => $this->title,
        ]);
    }
}
