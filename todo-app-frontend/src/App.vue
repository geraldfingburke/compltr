<template>
  <v-app>
    <AppHeader />
    <LoginForm class="my-auto" />
    <AppFooter />
  </v-app>
</template>

<script>
import AppHeader from "./components/AppHeader.vue"
import AppFooter from "./components/AppFooter.vue"
import LoginForm from "./components/LoginForm.vue"

export default {
  name: 'App',

  components: {
    AppHeader,
    AppFooter,
    LoginForm
  },

  data: () => ({
  }),
};
</script>
