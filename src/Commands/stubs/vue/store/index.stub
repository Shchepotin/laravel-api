/**
 * Vuex Store
 *
 * The {@link http://vuex.vuejs.org/en/index.html | store } of the application.
 */

import Vue from 'vue';
import Vuex from 'vuex';
import user from './modules/user';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    /**
     * Assign the modules to the store.
     */
    modules: {
        user,
    },

    /**
     * If strict mode should be enabled.
     */
    strict: debug,
});
