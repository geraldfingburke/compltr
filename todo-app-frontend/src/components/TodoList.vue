<template>
    <v-data-table :headers="headers" :items="todos" class="elevation-5">
        <template v-slot:top>
            <v-toolbar flat>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-select :items="filterSelect" v-model="todosFilterValue" label="Status"></v-select>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" class="mb-2 align--right" v-bind="attrs" v-on="on">
                            New Todo
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">{{ dialogFormTitle }}</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" v-if="hasError">
                                        <p color="red" class="text--center">{{ errorMessage }}</p>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.title" label="Todo">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="editedItem.description" label="Description">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-menu ref="menu" v-model="menu" :close-on-content-click="false"
                                            :return-value.sync="date" transition="scale-transition" offset-y
                                            min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="editedItem.dueDate" prepend-icon="mdi-calendar"
                                                    label="Due Date" readonly v-bind="attrs" v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker v-model="editedItem.dueDate" no-title scrollable>
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
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" text @click="close">
                                Cancel
                            </v-btn>
                            <v-btn v-if="(editedIndex == -1)" color="primary" text @click="makeTodo">
                                Create
                            </v-btn>
                            <v-btn v-else color="primary" text @click="updateTodo">
                                Update
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">
                            Are you sure you want to delete this todo?
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" text @click="closeDelete">
                                Cancel
                            </v-btn>
                            <v-btn color="primary" text @click="deleteTodoRequest">
                                OK
                            </v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:[`item.title`]="{ item }">
            <p class="text--disabled" v-if="item.complete"><s>{{ item.title }}</s></p>
            <p v-else>{{ item.title }}</p>
        </template>
        <template v-slot:[`item.description`]="{ item }">
            <p class="text--disabled" v-if="item.complete"><s>{{ item.description }}</s></p>
            <p v-else>{{ item.description }}</p>
        </template>
        <template v-slot:[`item.dueDate`]="{ item }">
            <p class="text--disabled" v-if="item.complete"><s>{{ formatDateUS(item.dueDate) }}</s></p>
            <p v-else>{{ formatDateUS(item.dueDate) }}</p>
        </template>
        <template v-slot:[`item.complete`]="{ item }">
            <v-simple-checkbox v-model="item.complete" @click="changeCompleteStatus(item.todoID, item.complete)">
            </v-simple-checkbox>
        </template>
        <template v-slot:[`item.edit`]="{ item }">
            <v-icon v-if="(item.complete != true)" small color="primary" class="mr-2"
                @click="editTodo(item)">mdi-pencil</v-icon>
            <v-icon small color="red" @click="deleteTodo(item)">mdi-delete</v-icon>
        </template>
    </v-data-table>
</template>

<script>
import axios from "axios";
import { mapGetters } from 'vuex';

export default {
    data() {
        return {
            dialog: false,
            dialogDelete: false,
            editedIndex: -1,
            editedItem: {
                todoID: "",
                title: "",
                description: "",
                dueDate: "",
                complete: ""
            },
            defaultItem: {
                todoID: "",
                title: "",
                description: "",
                dueDate: "",
                complete: "0"
            },
            date: "",
            menu: false,
            hasError: false,
            errorMessage: "",
            filterSelect: [
                { text: "All", value: null },
                { text: "Complete", value: true },
                { text: "Pending", value: false }
            ],
            todosFilterValue: null
        }
    },
    methods: {
        editTodo(todo) {
            this.editedIndex = this.todos.indexOf(todo);
            this.editedItem = Object.assign({}, todo);
            this.dialog = true;
        },
        deleteTodo(todo) {
            this.editedIndex = this.todos.indexOf(todo);
            this.editedItem = Object.assign({}, todo);
            this.dialogDelete = true;
        },
        async deleteTodoRequest() {
            await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "deleteTodo",
                userID: this.userID,
                todoID: this.editedItem.todoID,
            });
            this.$store.dispatch("getTodos");
            this.editedItem = {}
            this.dialogDelete = false;
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
            this.hasError = false;
            this.errorMessage = "";
        },
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        formatDateUS(date) {
            if (!date) {
                return "";
            }

            let newDate = date.split("-");
            newDate = newDate[1] + "/" + newDate[2] + "/" + newDate[0];
            return newDate;
        },
        async makeTodo() {
            if (!this.editedItem.title) {
                this.hasError = true;
                this.errorMessage = "A title is required!";
                return;
            }
            await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "makeTodo",
                userID: this.userID,
                title: this.editedItem.title,
                description: this.editedItem.description == "" ? null : this.editedItem.description,
                dueDate: this.editedItem.dueDate == "" ? null : this.editedItem.dueDate
            });
            this.$store.dispatch("getTodos");
            this.editedItem = this.defaultItem;
            this.dialog = false;
        },
        async changeCompleteStatus(todoID, complete) {
            complete = complete == true ? "1" : "0";
            await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "changeCompleteStatusTodo",
                todoID: todoID,
                complete: complete
            });
        },
        async updateTodo() {
            if (!this.editedItem.title) {
                this.hasError = true;
                this.errorMessage = "A title is required!";
                return;
            }
            await axios.post("https://geraldburke.dev/apis/todo-app/", {
                action: "updateTodo",
                todoID: this.editedItem.todoID,
                title: this.editedItem.title,
                description: this.editedItem.description,
                dueDate: this.editedItem.dueDate
            });
            this.$store.dispatch("getTodos");
            this.editedItem = this.defaultItem;
            this.dialog = false;
        },
        completeFilter(value) {
            if (this.todosFilterValue == null) {
                return true;
            }
            return value == this.todosFilterValue;
        }
    },
    computed: {
        ...mapGetters({
            userID: "userID",
            todos: "todos"
        }),
        dialogFormTitle() {
            return this.editedIndex == -1 ? "New Todo" : "Edit Todo";
        },
        headers() {
            return [
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
                    value: "complete",
                    filter: this.completeFilter
                },
                {
                    text: "Edit",
                    align: "center",
                    value: "edit",
                    sortable: "false"
                }
            ]
        }
    },
    watch: {
        dialog(value) {
            value || this.close();
        },
        dialogDelete(value) {
            value || this.closeDelete();
        }
    },
    created() {
        this.$store.dispatch("getTodos");
    }
}
</script>