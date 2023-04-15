<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectChildren extends Component
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
        $collectionToView = collect();
        foreach ($this->collection as $key => $element) {
            $collectionToView[$key] = $element['title'];
            if ($element['children']->count() > 0) {
                $collectionToView = $collectionToView->merge(
                    $this->renderChildren($element['children'], 1)
                );
            }
        }


        return view('components.inputs.select', [
            'name' => $this->name,
            'collection' => $collectionToView,
            'title' => $this->title,
            'selected' => $this->selected,
        ]);
    }

    public function renderChildren(Collection $collection, int $level): Collection
    {
        $childrenToView = collect();
        $tab = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);

        foreach ($collection as $key => $element) {
            $childrenToView[$key] = $tab . '→ ' . $element['title'];
            if ($element['children']->count() > 0) {
                $childrenToView = $childrenToView->merge(
                    $this->renderChildren($element['children'], $level + 1)
                );
            }
        }

        return $childrenToView;
    }
}
