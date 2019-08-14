<?php

namespace Arrowsgm\NovaFieldsGroup;

use Closure;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Panel;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

trait FieldsGroupForms
{
    /**
     * Resolve the creation fields.
     *
     * @param  NovaRequest $request
     * @return Collection
     */
    public function creationFields(NovaRequest $request)
    {
        $resolved = $this->resolveFields($request, function ($fields) {
            return $this->removeNonCreationFields($fields);
        });

        $withGroups = $this->resolveGroups($resolved, $request);
//        logger(print_r($withGroups,true));

        return $withGroups;
    }

    /**
     * Resolve the update fields.
     *
     * @param  NovaRequest $request
     * @return Collection
     */
    public function updateFields(NovaRequest $request)
    {
        $resolved = $this->resolveFields($request, function ($fields) {
            return $this->removeNonUpdateFields($fields);
        });

        $withGroups = $this->resolveGroups($resolved, $request);
//        logger(print_r($withGroups,true));

        return $withGroups;
    }

    /**
     * Compose fields group for resource
     *
     * @param Collection $fields
     * @param NovaRequest $request
     * @return Collection
     */
    protected function resolveGroups (Collection $fields, NovaRequest $request) {
        list($groups, $fields) = $fields->partition(function($field){
            return isset($field->meta['fieldsGroup']);
        });

        if (!$groups->isEmpty()) {
            $fields->put('NovaFieldsGroup', [
                'component' => 'nova-fields-group',
                'fields' => $groups,
                'panel'     => Panel::defaultNameForUpdate($request->newResource()),
            ]);
        }

        return $fields;
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Model     $model
     * @return array
     */
    public static function fill(NovaRequest $request, $model)
    {
        return static::fillFields(
            $request, $model,
            (new static($model))->parentCreationFields($request)
        );
    }

    /**
     * @param NovaRequest $request
     * @param $model
     * @return array
     */
    public static function fillForUpdate(NovaRequest $request, $model)
    {
        return static::fillFields(
            $request, $model,
            (new static($model))->parentUpdateFields($request)
        );
    }

    /**
     * @param NovaRequest $request
     * @return Collection
     */
    public function parentCreationFields(NovaRequest $request)
    {
        return parent::creationFields($request);
    }

    /**
     * @param NovaRequest $request
     * @return Collection
     */
    public function parentUpdateFields(NovaRequest $request)
    {
        return parent::updateFields($request);
    }

    /**
     * @param  NovaRequest $request
     * @return mixed
     */
    public static function rulesForCreation(NovaRequest $request)
    {
        return static::formatRules($request, (new static(static::newModel()))
                ->parentCreationFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getCreationRules($request);
                })->all());
    }

    /**
     * Get the validation rules for a resource update request.
     *
     * @param  NovaRequest $request
     * @return array
     */
    public static function rulesForUpdate(NovaRequest $request)
    {
        return static::formatRules($request, (new static(static::newModel()))
                ->parentUpdateFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getUpdateRules($request);
                })->all());
    }

    /**
     * Assign the fields with the given panels to their parent panel.
     *
     * @param  string                           $label
     * @param  Collection   $panels
     * @return Collection
     */
    protected function assignToPanels($label, Collection $panels)
    {
        return $panels->map(function ($field) use ($label) {
            return $field;
        });
    }
}
