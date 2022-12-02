<template>
    <div>
        <v-card class="mx-auto" width="33%" elevation="10" justify="center">
            <v-img width="100%" height="150px"
                src="https://images.unsplash.com/photo-1543196614-e046c7d3d82e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1041&q=80">
            </v-img>
            <v-form>
                <v-container>
                    <v-row class="justify-center">
                        <v-col cols="12" md="8">
                            <v-text-field v-model="userEmail" label="Email" append-icon="mdi-email"
                                required></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row class="justify-center">
                        <v-col cols="12" md="8">
                            <v-text-field v-model="userPassword" type="password" label="Password" append-icon="mdi-key"
                                required></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row class="text-center justify-center py-5">
                        <v-col cols="12" md="5">
                            <v-btn width="60%" color="primary" elevation="10" @click="signup()">Sign Up</v-btn>
                        </v-col>

                    </v-row>
                    <v-row class="text-center justify-center py-5">
                        <v-col cols="12" md="5">
                            <v-btn width="60%" elevation="10" @click="login()">Login</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      userEmail: "",
      userPassword: ""
    }
  },
  methods: {
    signup() {
        axios.post("https://geraldburke.dev/apis/todo-app/", {
            action: "signup",
            userEmail: this.userEmail,
            userPassword: this.userPassword
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function(error) {
            console.log(error);
        });
    },
    login() {
        axios.post("https://geraldburke.dev/apis/todo-app/", {
            action: "login",
            userEmail: this.userEmail,
            userPassword: this.userPassword
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function(error) {
            console.log(error);
        });
    }
  }
}
</script>