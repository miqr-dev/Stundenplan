<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
  praktikum: Object,
});

let form = useForm({
  id: props.praktikum.id,
  course_id: props.praktikum.course_id,
  name: props.praktikum.name,
  dateRange: [props.praktikum.start_date, props.praktikum.end_date],
  repeat_days: JSON.parse(props.praktikum.repeat_days), // Assuming it's a JSON string
});

let days = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag"];

const update = () => {
  form.patch(route("praktikum.update", form.id));
};

const destroy = () => {
  form.delete(route("praktikum.destroy", form.id));
};
</script>

<template>
  <BreezeAuthenticatedLayout>
    <Head title="Edit Praktikum" />
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
              <h2 class="text-h2">{{ props.praktikum.name }} bearbeiten</h2>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="update">
                <div class="mb-6">
                  <label
                    for="name"
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    >Name</label
                  >
                  <input
                    v-model="form.name"
                    id="name"
                    type="text"
                    class="w-full border rounded"
                  />
                  <div
                    v-if="form.errors.name"
                    class="text-red-500 text-sm mt-1"
                    v-text="form.errors.name"
                  ></div>
                </div>
                <div class="mb-6">
                  <!-- Your Checkbox inputs for repeat_days -->
                  <label
                    class="font-bold block mb-2 text-gray-600 text-xs uppercase"
                    >Wiederholungstage</label
                  >
                  <label
                    class="inline-block mr-2"
                    :key="day"
                    v-for="day in days"
                  >
                    <input
                      class="mr-1"
                      v-model="form.repeat_days"
                      :value="day"
                      type="checkbox"
                    />{{ day }}
                  </label>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    >Zeitraum</label
                  >
                  <Datepicker
                    v-model="form.dateRange"
                    locale="de"
                    cancelText="abbrechen"
                    selectText="auswählen"
                    week-numbersF
                    week-num-name="KW"
                    range
                    format="dd.MM.yyyy"
                    :min-date="praktikum.start_date"
                    :max-date="praktikum.end_date"
                    :partial-range="true"
                  ></Datepicker>
                </div>
                <div
                  v-if="form.errors.dateRange"
                  class="text-red-500 text-sm mt-1"
                  v-text="form.errors.dateRange"
                ></div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete
                    @click="destroy()"
                    buttonText="Praktikum löschen"
                  />
                  <!-- <button type="button" @click="destroy">Delete</button> -->
                  <SimpleSubmit :processing="form.processing"
                    >Aktualisieren</SimpleSubmit
                  >
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
