<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Select extends Component
{
    public array $list = [];
    public bool $dropdownOpened = false;
    public mixed $selected = null;
    public string $inputName = '';
    public mixed $searchInput = '';
    public string|null $title = null;

    public function select(mixed $value): void
    {
        $this->selected = $value;
        $this->setDropdownOpened(false);
    }

    public function setDropdownOpened(bool $value): void
    {
        $this->dropdownOpened = $value;
        if (!$value) $this->searchInput = '';
    }

    public function renderList(array $list, int $level = 1): string
    {
        $html = '';

        foreach ($list as $key => $element) {
            $html .= '
                <div wire:click="select(' . (is_int($key) ? $key : sprintf('\'%s\'', $key)) . ')"
                     class="border-b cursor-pointer py-2 px-4 pl-6 text-sm hover:bg-gray-100 transition"
                     style="padding-left: ' . $level . 'rem;"
                     >
                    <div class="font-medium">' . $element['title'] . '</div>
                    <div class="text-gray-500">' . $element['subtitle'] . '</div>
                </div>
            ';

            if (isset($element['children']))
                $html .= $this->renderList($element['children'], $level + 1);
        }

        return $html;
    }

    public function render(): View|Application|Factory
    {
        $selectedElem = null;
        if ($this->selected != null) {
            $selectedElem = $this->findSelected($this->selected, $this->list);
        }

        if (!empty($this->searchInput)) {
            $filteredList = [];
            foreach ($this->list as $key => $element) {
                if (
                    Str::contains(Str::lower($element['title']), Str::lower($this->searchInput))
                    || Str::contains(Str::lower($element['subtitle']), Str::lower($this->searchInput))
                ) {
                    $filteredList[$key] = $element;
                }
            }
        } else $filteredList = $this->list;

        return view('livewire.select', [
            'filteredList' => $filteredList,
            'dropdownOpened' => $this->dropdownOpened,
            'selected' => $this->selected,
            'selectedElem' => $selectedElem,
            'inputName' => $this->inputName,
        ]);
    }

    public function findSelected(mixed $selected, array|Collection $list): array|null
    {
        foreach ($list as $key => $element) {
            if ($key === $selected) return $element;
            if (isset($element['children'])) {
                $found = $this->findSelected($selected, $element['children']);
                if ($found != null) return $found;
            }
        }

        return null;
    }
}
