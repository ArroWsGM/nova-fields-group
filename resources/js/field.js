Nova.booting((Vue, router, store) => {
    // Vue.component('index-nova-fields-group', require('./components/IndexField'))
    Vue.component('detail-nova-fields-group', require('./components/DetailField'))
    Vue.component('form-nova-fields-group', require('./components/FormField'))
})
