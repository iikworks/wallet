<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Select extends Component
{
    private string $name;
    private Collection $collection;
    private string|null $title;
    private string $selected;

    /**
     * Create a new component instance.
     */
    public function __construct(string $name, Collection $collection, string|null $title = null, string $selected = '')
    {
        $this->name = $name;
        $this->collection = $collection;
        $this->title = $title;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.select', [
            'name' => $this->name,
            'collection' => $this->collection,
            'title' => $this->title,
            'selected' => $this->selected,
        ]);
    }
}
