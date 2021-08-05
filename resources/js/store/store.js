import Vue from 'vue'
import Vuex from 'vuex'

import parkingplaces from './parkingplaces.js'
import bookings from './bookings.js'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        parkingplaces,
        bookings
    }
  })