<template>
  <v-app>
    <AppHeader />
    <LoginForm class="my-auto" v-if="userID == '0'"/>
    <AddTodoForm />
    <TodoList />
    <AppFooter />
  </v-app>
</template>

<script>
import AppHeader from "./components/AppHeader.vue"
import AppFooter from "./components/AppFooter.vue"
import LoginForm from "./components/LoginForm.vue"
import AddTodoForm from "./components/AddTodoForm.vue"
import TodoList from "./components/TodoList.vue"
import { mapGetters } from "vuex";

export default {
  name: 'App',

  components: {
    AppHeader,
    AppFooter,
    LoginForm,
    AddTodoForm,
    TodoList
  },

  data: () => ({
  }),
  computed: {
    ...mapGetters({
      userID: "userID"
    })
  }
};
</script>
