import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        userID: 0,
        todos: []
    },
    getters: {
        userID(state) {
            return state.userID;
        },
        todos(state) {
            return state.todos;
        }
    },
    mutations: {
        async LOGIN(state, userID) {
            state.userID = userID;
        },
        async LOGOUT(state) {
            state.userID = 0;
        },
        async SETTODOS(state, todos) {
            state.todos = todos;
        }
    },
    plugins: [createPersistedState()]
});