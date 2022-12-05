<template>
    <v-container class="mt-15">
        <v-row>
            <v-col cols="2">
                <v-text-field label="Todo" v-model="title"></v-text-field>
            </v-col>
            <v-col cols="7">
                <v-text-field label="Description" v-model="description"></v-text-field>
            </v-col>
            <v-col cols="2">
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date"
                    transition="scale-transition" offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="dueDate" prepend-icon="mdi-calendar" label="Due Date" readonly
                            v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="dueDate" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">
                            Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(date)">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="1">
                <v-btn class="my-4" @click="addTodo()"><v-icon color="primary">mdi-arrow-right-bold-box-outline</v-icon></v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            title: "",
            description: "",
            dueDate: ""
        }
    },
    methods: {
        async addTodo() {
            axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "makeTodo",
                userID: this.userID,
                title: this.title,
                description: this.description == "" ? null : this.description,
                dueDate: this.dueDate == "" ? null : this.dueDate
            });
            this.title = "";
            this.description = "";
            this.dueDate = "";
            this.$store.dispatch("getTodos");
        }
    },
    computed: {
        ...mapGetters({
            userID: "userID"
        })
    }
}
</script>