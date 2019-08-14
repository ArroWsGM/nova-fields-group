<?php

namespace Arrowsgm\NovaFieldsGroup;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Panel;

class FieldsGroup extends Panel
{
    public $html_class = '';
    public $no_labels = false;

    /**
     * Prepare the given fields.
     *
     * @param \Closure|array $fields
     * @return array
     */
    protected function prepareFields($fields)
    {
        return collect(is_callable($fields) ? $fields() : $fields)->each(function ($field) {
            $field->panel = $this->name;
            $field->withMeta([
                'fieldsGroup' => true,
            ]);
        })->all();
    }
    /**
     * Create a new panel instance.
     *
     * @param string $name
     * @param \Closure|array $fields
     * @return void
     */
//    public function __construct($name, $fields = [])
//    {
//        $this->component = 'nova-fields-group';
//
//        parent::__construct($name, $this->prepareFields($fields));
//    }

    /**
     * This method removes the labels from each subfield.
     *
     * @return FieldsGroup
     */
    public function nolabel()
    {
        $this->nolabel = true;

        return $this;
    }

    /**
     * This method removes the labels from each subfield.
     *
     * @param string $class
     * @return FieldsGroup
     */
    public function class(string $class)
    {
        $this->html_class = $class;

        return $this;
    }

    /**
     * Display the toolbar when showing this panel.
     *
     * @return $this
     */
    public function withToolbar()
    {
        return $this;
    }

    /**
     * Prepare the panel for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'class'     => $this->html_class,
            'noLabels'  => $this->no_labels,
        ]);
    }
}
