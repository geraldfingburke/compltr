import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        userID: 0
    },
    getters: {
        userID(state) {
            return state.userID;
        }
    },
    mutations: {
        async LOGIN(state, userID) {
            state.userID = userID;
        },
        async LOGOUT(state) {
            state.userID = 0;
        }
    },
    plugins: [createPersistedState()]
});