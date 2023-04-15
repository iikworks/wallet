<?php

namespace App\View\Components\PageStruct;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    private string|null $link;
    private string|null $linkTitle;

    /**
     * Create a new component instance.
     */
    public function __construct(string $link = null, string $linkTitle = null)
    {
        $this->link = $link;
        $this->linkTitle = $linkTitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-struct.table', [
            'link' => $this->link,
            'linkTitle' => $this->linkTitle,
        ]);
    }
}
