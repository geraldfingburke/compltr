import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import axios from 'axios'


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
    actions: {
        async getTodos (context) {
            const todoList = await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "getTodos",
                userID: context.getters.userID
            });
            console.log(todoList.data);
            context.commit("SETTODOS", todoList.data)
        }
    },
    plugins: [createPersistedState()]
});