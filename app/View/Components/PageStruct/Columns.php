<?php

namespace App\View\Components\PageStruct;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Columns extends Component
{
    private int $minCols;
    private int $mdCols;
    private int $lgCols;
    private int $gap;

    /**
     * Create a new component instance.
     */
    public function __construct(int $minCols = 1, int $mdCols = 2, int $lgCols = 4, int $gap = 4)
    {
        $this->minCols = $minCols;
        $this->mdCols = $mdCols;
        $this->lgCols = $lgCols;
        $this->gap = $gap;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.page-struct.columns', [
            'minCols' => $this->minCols,
            'mdCols' => $this->mdCols,
            'lgCols' => $this->lgCols,
            'gap' => $this->gap,
        ]);
    }
}
