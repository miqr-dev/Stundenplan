<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, onMounted } from "vue";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";
import { provide } from "vue";

provide("parentComponent", "Edit");

const props = defineProps({
  teacher: Object,
  subjects: Array,
  cities: Object,
});

const showSubjects = ref(true);

const form = useForm({
  id: props.teacher.id,
  title: props.teacher.title,
  name: props.teacher.name,
  surname: props.teacher.surname,
  email: props.teacher.email,
  color: props.teacher.color,
  maxhourweek: props.teacher.maxhourweek,
  subjects: props.teacher.subjects,
  cities: props.teacher.cities,
  teacherNotAvailable: props.teacher.teacherNotAvailable,
});

const update = () => {
  form.patch(route("teacher.update", form.id));
};

const destroy = () => {
  form.delete(route("teacher.destroy", form.id));
};
</script>


<template>
  <Head title="Lehrer" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8 xl:px-12">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8 xl:px-12">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray w-full xl:w-3/4 lg:w-4/5 md:w-5/6 sm:w-full mx-auto">
            <div class="flex justify-between">
              <h2 class="text-h2">Bearbeiten {{ form.name }}, {{ form.surname }}</h2>
              <SimpleBack />
            </div>
            <div class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
              <form @submit.prevent="update">
<div class="mb-6 flex items-center space-x-2"> <!-- space-x-2 for spacing between flex items -->
  <!-- Title Select Dropdown -->
  <div class="w-24">
    <label
      class="block mb-2 text-xs font-bold text-gray-600 uppercase"
      for="title"
    >
      Title
    </label>
    <select v-model="form.title" class="form-input w-full" id="title">
      <option value="Frau">Frau</option>
      <option value="Herr">Herr</option>
      <!-- ... other options ... -->
    </select>
    <div
      v-if="form.errors.title"
      v-text="form.errors.title"
      class="text-red-500 text-sm mt-1"
    ></div>
  </div>

  <!-- Name Input -->
  <div class="flex-1">
    <SimpleInput
      v-model="form.name"
      label="Name"
      type="text"
      :error="form.errors.name"
    />
  </div>
  
  <!-- Surname Input -->
  <div class="flex-1">
    <SimpleInput
      v-model="form.surname"
      label="Surname"
      type="text"
      :error="form.errors.surname"
    />
  </div>
</div>

                <div class="mb-6">
                  <SimpleInput
                    v-model="form.maxhourweek"
                    label="Max. Stunden / Woche"
                    type="text"
                    :error="form.errors.maxhourweek"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.email"
                    label="E-Mail"
                    type="text"
                    :error="form.errors.email"
                  />
                </div>
                <SimpleItemSelector
                  v-model="form.subjects"
                  :items="props.subjects"
                  :searchable="true"
                  label="subject"
                  displayLabel="Fach"
                ></SimpleItemSelector>
                <SimpleItemSelector
                  v-model="form.cities"
                  :items="props.cities"
                  :searchable="true"
                  label="city"
                  displayLabel="Stadt"
                ></SimpleItemSelector>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Dozent/in löschen" />
                  <SimpleSubmit :processing="form.processing">Aktualisieren</SimpleSubmit>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>