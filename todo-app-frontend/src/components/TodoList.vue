<template>
    <v-data-table :headers="headers" :items="todos">

    </v-data-table>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            headers: [
                    {
                        text: "Todo",
                        align: "Start",
                        sortable: "false",
                        value: "title"
                    },
                    {
                        text: "Description",
                        sortable: "false",
                        value: "description"
                    },
                    {
                        text: "Due Date",
                        value: "dueDate"
                    },
                    {
                        text: "Complete",
                        value: "complete"
                    }
                ]
            }
    },
    methods: {
        async getTodos() {
            const todoList = await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "getTodos",
                userID: this.userID
            });
            this.$store.commit("SETTODOS", todoList.data);
        }
    },
    computed: {
        ...mapGetters({
            userID: "userID",
            todos: "todos"
        })
    },
    created () {
        this.getTodos();
    }
}
</script>