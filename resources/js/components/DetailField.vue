<template>
    <div v-if="field.fields.length > 0">
        <component
            v-for="(childField, index) in field.fields"
            :is="resolveComponentName(childField)"
            :class="{ 'remove-bottom-border': index === field.fields.length - 1 }"
            :key="index"
            :resource-id="resourceId"
            :resource-name="resourceName"
            :resource="resource"
            :field="childField"
            @actionExecuted="actionExecuted"
        />
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    methods: {
        /**
         * Resolve the component name.
         */
        resolveComponentName(field) {
            return field.prefixComponent ? 'detail-' + field.component : field.component
        },

        /**
         * Handle the actionExecuted event and pass it up the chain.
         */
        actionExecuted() {
            this.$emit('actionExecuted')
        },
    },
}
</script>
