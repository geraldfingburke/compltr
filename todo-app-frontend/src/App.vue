<template>
  <v-app>
    <v-app-bar
      app
      color="gray"
      dark
    >
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
      <v-toolbar-title>Compltr</v-toolbar-title>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      absolute
      temporary
    >
      <v-list
        nav
        dense
      >
        <v-list-item>
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Home</v-list-item-title>
        </v-list-item>

        <v-list-item absolute>
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Login</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-card
    class="mx-auto my-auto"
    width="33%"
    elevation="10"
    justify="center"
  >
  <v-img
    width="100%"
    height="150px"
    src="https://images.unsplash.com/photo-1543196614-e046c7d3d82e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1041&q=80"
  >
  </v-img>
  <v-form>
    <v-container>
      <v-row class="justify-center">
        <v-col
          cols="12"
          md="8"
        >
          <v-text-field
            v-model="userEmail"
            label="Email"
            append-icon="mdi-email"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row class="justify-center">
        <v-col
          cols="12"
          md="8"
        >
          <v-text-field
            v-model="userPassword"
            type="password"
            label="Password"
            append-icon="mdi-key"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row class="text-center justify-center py-5">
        <v-col
          cols="12"
          md="5"
        >
          <v-btn width="60%" color="primary" elevation="10">Sign Up</v-btn>
        </v-col>

      </v-row>
      <v-row class="text-center justify-center py-5">
        <v-col
          cols="12"
          md="5"
        >
          <v-btn width="60%" elevation="10">Login</v-btn>
        </v-col>
      </v-row>
           
    </v-container>
  </v-form>

  </v-card>

    <v-footer color="gray" fixed="true">
      <v-row
      justify="center"
      no-gutters
    >
      <v-col
        class="py-4 text-center"
        cols="12"
      >
      <strong>{{ new Date().getFullYear() }} — Gerald Burke</strong>
      </v-col>
    </v-row>
    </v-footer>

  </v-app>
</template>

<script>

export default {
  name: 'App',

  components: {
    
  },

  data: () => ({
    drawer: false
  }),
};
</script>
