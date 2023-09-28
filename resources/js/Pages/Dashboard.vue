<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { PencilIcon, CheckIcon } from "@heroicons/vue/20/solid";

const todos = ref([]);
const newTodo = ref("");

const props = defineProps({
  todos: Array,
});

const editTodo = (todo) => {
  todo.edit = true;
  todo.tempText = todo.text;
};

const saveTodo = (todo) => {
  if (todo.tempText.trim() !== "") {
    // Update `text` on the server, do not change local state directly
    router.put(`/todos/${todo.id}`, { text: todo.tempText });
  }
  todo.edit = false;
};

const toggleTodo = (todo) => {
  // Update `is_done` on the server, do not change local state directly
  router.put(`/todos/${todo.id}`, { is_done: !todo.is_done });
};

const addTodo = () => {
  if (newTodo.value.trim() === "") return;
  router.post("/todos", { text: newTodo.value }, {
    onSuccess: () => {
      newTodo.value = ""; // Clear the input after adding the new todo
    }
  });
};
</script>

<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">ToDo</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <input
              v-model="newTodo"
              @keyup.enter="addTodo"
              class="px-4 py-2 border rounded-md w-full"
              placeholder="Was hast'n vor?"
            />
            <ul class="mt-4">
              <li
                v-for="todo in props.todos"
                :key="todo.id"
                class="mb-2 flex items-center"
              >
                <input
                  type="checkbox"
                  :checked="todo.is_done"
                  @change="toggleTodo(todo)"
                  class="form-checkbox h-5 w-5 text-blue-600"
                />

                <template v-if="!todo.edit">
                  <span
                    :class="{ 'line-through': todo.is_done }"
                    class="ml-2 flex-grow"
                  >
                    {{ todo.text }}
                  </span>
                  <button
                    @click="editTodo(todo)"
                    class="ml-2 text-blue-500 hover:text-blue-700"
                  >
                    <PencilIcon class="h-5 w-5" />
                  </button>
                </template>

                <template v-else>
                  <input
                    v-model="todo.tempText"
                    @keyup.enter="saveTodo(todo)"
                    class="ml-2 flex-grow form-input py-1 px-2"
                  />
                  <button
                    @click="saveTodo(todo)"
                    class="ml-2 text-blue-500 hover:text-green-700"
                  >
                    <CheckIcon class="h-5 w-5" />
                  </button>
                </template>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<style>
/* If you don’t want to use Tailwind for styling the line-through, you can add a style here. */
</style>
