<script setup>
import { Link, Head, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import { ref, computed, watch } from "vue";
import SimpleConfirm from "@/Components/SimpleConfirm.vue";

const selectedTemplate = computed(() => {
  if (form.template_id) {
    return props.templates.find((template) => template.id === form.template_id);
  }
  return null;
});

const selectedSubjects = computed(() => {
  if (selectedTemplate.value) {
    return selectedTemplate.value.subjects;
  }
  return [];
});

const props = defineProps({
  templates: {},
  grids: {},
  locations: {},
  rooms: {},
});

const form = useForm({
  name: "",
  courseDate: [],
  lbrn: "",
  color: "",
  type: "",
  template_id: "",
  room_id: "",
  grid_id: "",
  subjects: [],
});

watch(selectedSubjects, (newSubjects) => {
  form.subjects = newSubjects.map((subject) => ({
    id: subject.id,
    soll: subject.default_soll,
  }));
});

const showConfirm = ref(false);

const handleConfirm = () => {
  showConfirm.value = false;
  form.post('/course');
};
const handleCancel = () => {
  showConfirm.value = false;
};
const handleSubmit = (e) => {
  e.preventDefault();
  showConfirm.value = true;
};
</script>

<template>
  <Head title="Course" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Add a new Course</h2>
              <Link :href="route('course.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="handleSubmit">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.lbrn"
                    label="lbrn"
                    type="text"
                    :error="form.errors.lbrn"
                  />
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Course date
                  </label>
                  <Datepicker
                    class="w-full p-2 border border-gray-400 bg-white rounded"
                    v-model="form.courseDate"
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
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.type"
                    label="type"
                    type="text"
                    :error="form.errors.type"
                  />
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="color"
                  >
                    Color
                  </label>
                  <VSwatches v-model="form.color" swatches="text-advanced" />
                  <div
                    v-if="form.errors.color"
                    v-text="form.errors.color"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="template"
                  >
                    Template
                  </label>
                  <select
                    v-model="form.template_id"
                    class="w-full p-2 border border-gray-400 rounded"
                    id="template"
                    name="template"
                  >
                    <option value="">Please select a Template</option>
                    <option
                      v-for="template in props.templates"
                      :value="template.id"
                    >
                      {{ template.name }}
                    </option>
                  </select>
                  <div
                    v-if="form.errors.template_id"
                    v-text="form.errors.template_id"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="room"
                  >
                    Room
                  </label>
                  <select
                    v-model="form.room_id"
                    class="w-full p-2 border border-gray-400 rounded hover:border-gray-500"
                    id="room"
                    name="room"
                  >
                    <option value="" class="hover:bg-gray-200 hover:text-black">
                      Please select
                    </option>
                    <optgroup
                      v-for="location in props.locations"
                      :key="location.id"
                      :label="location.name"
                    >
                      <option
                        v-for="room in props.rooms.filter(
                          (room) => room.location_id === location.id
                        )"
                        :key="room.id"
                        :value="room.id"
                        class="hover:bg-gray-200 hover:text-black hover:font-bold"
                      >
                        {{ room.name }} {{ room.room_number }}
                      </option>
                    </optgroup>
                  </select>
                  <div
                    v-if="form.errors.room_id"
                    v-text="form.errors.room_id"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="grid"
                  >
                    Grid
                  </label>
                  <select
                    v-model="form.grid_id"
                    class="w-full p-2 border border-gray-400 rounded"
                    id="grid"
                    name="grid"
                  >
                    <option value="">Please select a Grid</option>
                    <option v-for="grid in props.grids" :value="grid.id">
                      {{ grid.name }}
                    </option>
                  </select>
                  <div
                    v-if="form.errors.grid_id"
                    v-text="form.errors.grid_id"
                    class="text-red-500 text-sm mt-1"
                  ></div>
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
    <div
      v-if="selectedTemplate"
      class="fixed top-1/2 right-2 transform -translate-y-1/2 py-2 px-4 bg-white border border-gray-400 rounded-xl shadow text-gray-700 z-10 md:max-w-[150px] lg:max-w-[250px] xl:min-w-[460px]"
    >
      <h2>
        <span class="text-blue-400 text-h2 text-bold">{{
          selectedTemplate.name
        }}</span>
      </h2>
      <div v-if="selectedSubjects.length > 0" class="mb-6">
        <label
          class="block my-2 text-xs font-bold text-gray-600 uppercase"
          for="subjects"
        >
          Subjects
        </label>
        <ul class="list-inside list-decimal space-y-1">
          <li v-for="subject in selectedSubjects" :key="subject.id">
            {{ subject.name }} -
            <span class="text-bold text-blue-500"> Soll: </span>
            <span v-if="!subject.editing" @dblclick="subject.editing = true">
              {{ form.subjects.find((s) => s.id === subject.id).soll }}
            </span>
            <input
              v-if="subject.editing"
              v-model="form.subjects.find((s) => s.id === subject.id).soll"
              @blur="subject.editing = false"
              type="number"
              class="border rounded p-1 w-16"
              autofocus
            />
          </li>
        </ul>
      </div>
    </div>
<SimpleConfirm
  :open="showConfirm"
  title="Confirm Submission"
  message="Make sure you chose the right Template, after Confirming the Template you have chosen can not be changed !!!"
  @confirm="handleConfirm"
  @cancel="handleCancel"
/>
  </BreezeAuthenticatedLayout>
</template>
