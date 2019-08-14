<template>
    <div v-if="field.fields.length > 0">
        <component
            v-for="(childField, index) in field.fields"
            :is="`index-${childField.component}`"
            :class="{ 'remove-bottom-border': index === field.fields.length - 1 }"
            :key="index"
            :resource-name="resourceName"
            :field="childField"
        />
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
