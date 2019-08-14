<template>
    <div v-if="field.fields.length > 0"
         :class="field.class"
    >
        <component
            v-for="(childField, index) in field.fields"
            :is="`form-${childField.component}`"
            :class="{ 'remove-bottom-border': index === field.fields.length - 1 }"
            :key="index"
            :errors="errors"
            :resource-id="resourceId"
            :resource-name="resourceName"
            :field="childField"
            :via-resource="viaResource"
            :via-resource-id="viaResourceId"
            :via-relationship="viaRelationship"
            @file-deleted="$emit('update-last-retrieved-at-timestamp')"
        />
    </div>
</template>

<script>
    import {
        FormField,
        HandlesValidationErrors,
        InteractsWithResourceInformation
    } from 'laravel-nova'

    export default {
        mixins: [
            FormField,
            HandlesValidationErrors,
            InteractsWithResourceInformation
        ],
        props: {
            name: {default: 'Panel'},
            panel: {},
            fields: {type: Array,default: ()=>{return []}},
            errors: {type: Object, required: true},
            resourceId: {},
            viaResource: {type: String},
            viaResourceId: {},
            viaRelationship: {},
        },
        created() {
            console.log('field')
            this.fields.forEach(field=>{
                field.panel = this.field.panel
            })
        },
        mounted() {
            let wrapper = this.$parent.$el
            if (wrapper && wrapper.classList.contains('card')){
                wrapper.classList.add('flex')
            }
            // this.$el.classList.remove('nova-grid-card-styles')
        },
        methods: {
            setChildrenValue(index,event) {
                console.log(index,event)
            },

            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value
            }
        }
    }
</script>
