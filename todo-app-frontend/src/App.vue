<template>
  <v-app>
    <v-container>
      <v-row>
        <AppHeader />
      </v-row>
      <v-row class="mt-16">
        <v-col cols="12">
          <LoginForm class="mt-16" v-if="userID == '0'"/>
          <TodoList class="my-auto" v-if="userID != '0'"/>
        </v-col>
      </v-row>
      <v-row>
        <AppFooter />
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
import AppHeader from "./components/AppHeader.vue"
import AppFooter from "./components/AppFooter.vue"
import LoginForm from "./components/LoginForm.vue"
import TodoList from "./components/TodoList.vue"
import { mapGetters } from "vuex";

export default {
  name: 'App',

  components: {
    AppHeader,
    AppFooter,
    LoginForm,
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
