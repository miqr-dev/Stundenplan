<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";
import { provide } from "vue";

provide("parentComponent", "Create");

const props = defineProps({
  subject: Object,
  templates: Array,
  subject_template: Array,
  available_teachers: Array,
});

const form = useForm({
  id: props.subject.id,
  name: props.subject.name,
  color: props.subject.color,
  default_soll: props.subject.default_soll,
  templates: props.subject.templates,
  subject_template: props.subject_template,
  teachers: props.subject.teachers,
});

const update = () => {
  form.patch(route("subject.update", form.id));
};

const destroy = () => {
  form.delete(route("subject.destroy", form.id));
};
</script>

<template>
  <Head title="Fächer" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="min-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 xl:text-h1 lg:text-h2 md:text-h3 font-bold text-p-gray h-full w-full xl:w-2/3 lg:w-3/4 md:w-full shadow-md mx-auto"
          >
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-h2">Fach bearbeiten</h2>
              <SimpleBack />
            </div>
            <div class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
              <form @submit.prevent="update">
                <div class="mb-6 w-1/2">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="mb-6 w-1/2">
                  <SimpleInput
                    v-model="form.default_soll"
                    label="Soll Stunden"
                    type="text"
                    :error="form.errors.default_soll"
                  />
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Farbe
                  </label>
                  <VSwatches
                    v-model="form.color"
                    swatches="text-advanced"
                  ></VSwatches>
                  <div v-if="form.errors.color" v-text="form.errors.color" class="text-red-500 text-sm mt-1"></div>
                </div>
                <SimpleItemSelector
                  v-model="form.templates"
                  :items="props.templates"
                  :searchable="true"
                  label="Vorlage"
                  displayLabel="Vorlage/-n"
                ></SimpleItemSelector>
                <SimpleItemSelector
                  v-model="form.teachers"
                  :items="props.available_teachers"
                  :searchable="true"
                  label="Dozent"
                  displayLabel="Dozent/en"
                ></SimpleItemSelector>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Löschen" :processing="form.processing" />
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

<style scoped>
:deep(.vue-swatches__row) {
  display: flex;
  flex-wrap: nowrap;
  gap: 0;
}

:deep(.vue-swatches__swatch) {
  margin: 0 !important;
  border-width: 2px !important;
}
</style>
