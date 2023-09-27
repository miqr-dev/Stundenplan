<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
  course: {},
});

let form = useForm({
  course_id: props.course.id,
  name: "",
  dateRange: [],
  repeat_days: [],
});

let days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag'];

let submit = () => {
  form.post("/praktikum");
};
</script>


<template>
  <BreezeAuthenticatedLayout>
    <Head title="Praktikum" />
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
              <h2 class="text-h2">Neues Praktikum </h2>
              <Link
                :href="route('course.show', { course: course.id })"
                class="text-blue-500 text-p"
              >X</Link>
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="submit">
                <div class="mb-6">
                  <label for="name" class="block mb-2 text-xs font-bold text-gray-600 uppercase">Name</label>
                  <input v-model="form.name" id="name" type="text" class="w-full border rounded" />
                  <div v-if="form.errors.name" class="text-red-500 text-sm mt-1" v-text="form.errors.name"></div>
                </div>
                <div class="mb-6">
                  <label class="block mb-2 text-xs font-bold text-gray-600 uppercase">Wiederholungstage</label>
                  <label v-for="day in days" :key="day" class="inline-block mr-2">
                    <input type="checkbox" :value="day" v-model="form.repeat_days" class="mr-1" />{{ day }}
                  </label>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Zeitraum
                  </label>
                  <Datepicker
                    v-model="form.dateRange"
                    locale="de"
                    cancelText="abbrechen"
                    selectText="auswählen"
                    week-numbers
                    week-num-name="KW"
                    range
                    format="dd.MM.yyyy"
                    :min-date="course.start_date"
                    :max-date="course.end_date"
                    :partial-range="true"
                  ></Datepicker>
                </div>
                <div
                  v-if="form.errors.dateRange"
                  class="text-red-500 text-sm mt-1"
                  v-text="form.errors.dateRange"
                ></div>
                <div class="mb-1 text-right">
                  <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-500 text-white rounded">
                    Einreichen
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

