require('./bootstrap');

// require('alpinejs');

// Require Vue
window.Vue = require('vue').default;

import store from './store/store.js'

// Register Vue Components
Vue.component('parking-places', require('./components/ParkingPlaces.vue').default);
Vue.component('spinner', require('./components/Spinner.vue').default);
Vue.component('bookings', require('./components/Bookings.vue').default);

// Initialize Vue
const app = new Vue({
    el: '#app',
    store: store
});
