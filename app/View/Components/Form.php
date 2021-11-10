<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * The input value.
     *
     * @var object
     */
    public $row;

    /**
     * The input name.
     *
     * @var string
     */
    public $name;

    /**
     * The input label.
     *
     * @var string
     */
    public $label;

    /**
     * The input type.
     *
     * @var string
     */
    public $type;
    
    /**
     * The seleced item.
     */
    public $selected;

    /**
     * The checked item.
     */
    public $checked;


    /**
     * The select values.
     *
     * @var array
     */
    public $values;


    /**
     * The attributes.
     *
     * @var string
     */
    public $attributes;

        
    /**
     * imageSource
     *
     * @var string
     */
    public $imageSource;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form');
    }
}
