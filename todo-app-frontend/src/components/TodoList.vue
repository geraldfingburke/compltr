<template>
    <v-data-table :headers="headers" :items="todos">
        <template v-slot:[`item.complete`]="{ item }">
            <v-simple-checkbox v-model="item.complete" true-value="1" false-value="0">
            </v-simple-checkbox>
        </template>
    </v-data-table>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            headers: [
                    {
                        text: "Todo",
                        sortable: false,
                        value: "title"
                    },
                    {
                        text: "Description",
                        sortable: false,
                        value: "description"
                    },
                    {
                        text: "Due Date",
                        value: "dueDate"
                    },
                    {
                        text: "Complete",
                        align: "center",
                        value: "complete"
                    }
                ]
            }
    },
    computed: {
        ...mapGetters({
            userID: "userID",
            todos: "todos"
        })
    },
    created () {
        this.$store.dispatch("getTodos");
    }
}
</script>