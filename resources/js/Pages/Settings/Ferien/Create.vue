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
  ferienDate: [],
});

let submit = () => {
  form.post("/ferien");
};
</script>

<template>
  <Head title="Ferien" />

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
              <h2 class="text-h2">Neuer Ferienzeitraum </h2>
              <Link
                :href="route('course.show', { course: course.id })"
                class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="submit">
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Zeitraum
                  </label>
                  <Datepicker
                    v-model="form.ferienDate"
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
                  v-if="form.errors.ferienDate"
                  class="text-red-500 text-sm mt-1"
                  v-text="form.errors.ferienDate"
                ></div>
                <div class="mb-1 text-right">
                  <SimpleSubmit :processing="form.processing" Einreichen />
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
