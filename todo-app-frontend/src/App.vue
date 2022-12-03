<template>
  <v-app>
    <AppHeader />
    <LoginForm class="my-auto" v-if="userID == '0'"/>

    <v-container class="mt-15">
      <v-row>
        <v-col cols="2">
          <v-text-field label="Todo"></v-text-field>
        </v-col>
        <v-col cols="7">
          <v-text-field label="Description"></v-text-field>
        </v-col>
        <v-col cols="2">
          <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="date"
            prepend-icon="mdi-calendar"
            label="Due Date"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="date"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menu = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.menu.save(date)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
        </v-col>
        <v-col cols="1">
          <v-btn class="my-4"><v-icon color="primary">mdi-arrow-right-bold-box-outline</v-icon></v-btn>
        </v-col>
      </v-row>
    </v-container>

    <AppFooter />
  </v-app>
</template>

<script>
import AppHeader from "./components/AppHeader.vue"
import AppFooter from "./components/AppFooter.vue"
import LoginForm from "./components/LoginForm.vue"
import { mapGetters } from "vuex";

export default {
  name: 'App',

  components: {
    AppHeader,
    AppFooter,
    LoginForm
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
