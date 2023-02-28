<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
  teacher: {},
});

let form = useForm({
  teacher_id: props.teacher.id,
  awayDate: [],
  fulltime: "",
});

let submit = () => {
  form.post("/teacher-not-available");
};
</script>

<template>
  <Head title="Leaves" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Add a new Leave</h2>
              <Link
                :href="route('teacher.show', { teacher: teacher.id })"
                class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="submit">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.fulltime"
                    label="Fulltime"
                    type="text"
                    :error="form.errors.fulltime"
                  />
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Date Range
                  </label>
                  <Datepicker
                    v-model="form.awayDate"
                    locale="de"
                    cancelText="abbrechen"
                    selectText="auswählen"
                    week-numbers
                    week-num-name="KW"
                    range
                    format="dd.MM.yyyy"
                    :partial-range="true"
                  ></Datepicker>
                </div>
                <div
                  v-if="form.errors.awayDate"
                  class="text-red-500 text-sm mt-1"
                  v-text="form.errors.awayDate"
                ></div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Time Slots
                  </label>
                  <div class="flex flex-row">
                    <div class="flex flex-col mr-3 w-1/3">
                      <label
                        class="block text-sm font-medium leading-5 text-gray-700"
                        for="start-time-1"
                      >
                        <input
                          id="start-time-1"
                          type="checkbox"
                          class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                        />
                        <span class="ml-2">8:00 - 9:30</span>
                      </label>
                      <label
                        class="block text-sm font-medium leading-5 text-gray-700"
                        for="start-time-2"
                      >
                        <input
                          id="start-time-2"
                          type="checkbox"
                          class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                        />
                        <span class="ml-2">9:40 - 11:10</span>
                      </label>
                      <label
                        class="block text-sm font-medium leading-5 text-gray-700"
                        for="start-time-3"
                      >
                        <input
                          id="start-time-3"
                          type="checkbox"
                          class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                        />
                        <span class="ml-2">11:15 - 12:00</span>
                      </label>
                    </div>
                    <div class="flex flex-col w-1/3">
                      <label
                        class="block text-sm font-medium leading-5 text-gray-700"
                        for="start-time-4"
                      >
                        <input
                          id="start-time-4"
                          type="checkbox"
                          class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                        />
                        <span class="ml-2">12:40 - 14:10</span>
                      </label>
                      <label
                        class="block text-sm font-medium leading-5 text-gray-700"
                        for="start-time-5"
                      >
                        <input
                          id="start-time-5"
                          type="checkbox"
                          class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                        />
                        <span class="ml-2">14:15 - 13:00</span>
                      </label>
                    </div>
                    <div class="flex flex-col w-1/3">
                      <p class="text-sm font-medium leading-5 text-gray-700">
                        Leave the selection empty if you are absent the whole
                        day.
                      </p>
                    </div>
                  </div>
                </div>
                <div class="mb-1 text-right">
                  <SimpleSubmit :processing="form.processing" Submit />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<!-- .checkbox:checked + label:before {
  content: "✔";
  color: #fff;
  text-align: center;
  line-height: 20px;
} -->
