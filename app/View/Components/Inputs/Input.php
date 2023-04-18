<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    private string $type;
    private string $name;
    private string $placeholder;
    private string $value;
    private string $classes;
    private string $step;
    private string $min;
    private string $max;
    private string|null $title;
    private bool $isRequired;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type, string $name, string $placeholder = '', string $value = '', string $classes = '', string $step = '', string $min = '', string $max = '', string|null $title = null, bool $isRequired = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->classes = $classes;
        $this->step = $step;
        $this->min = $min;
        $this->max = $max;
        $this->title = $title;
        $this->isRequired = $isRequired;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.input', [
            'type' => $this->type,
            'name' => $this->name,
            'placeholder' => $this->placeholder,
            'value' => $this->value,
            'classes' => $this->classes,
            'step' => $this->step,
            'min' => $this->min,
            'max' => $this->max,
            'title' => $this->title,
            'isRequired' => $this->isRequired,
        ]);
    }
}
