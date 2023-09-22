<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";
import { provide } from "vue";

provide("parentComponent", "Edit");

const props = defineProps({
  template: Object,
  subjects: Array,
});

const form = useForm({
  id: props.template.id,
  name: props.template.name,
  subjects: props.template.subjects,
});

const update = () => {
  form.patch(route("template.update", form.id));
};

const destroy = () => {
  form.delete(route("template.destroy", form.id));
};
</script>

<template>
  <Head title="Vorlage bearbeiten" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="min-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 xl:text-h1 lg:text-h2 md:text-h3 font-bold text-p-gray h-full w-full xl:w-2/3 lg:w-3/4 md:w-full shadow-md mx-auto">
     <div class="flex justify-between items-center mb-4">
        <h2 class="text-h2">Vorlage bearbeiten</h2>
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
                <SimpleItemSelector
                  v-model="form.subjects"
                  :items="props.subjects"
                  :searchable="true"
                  label="Fach"
                  :sollValues="form.subject_template"
                ></SimpleItemSelector>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Löschen" />
                  <SimpleSubmit :processing="form.processing" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
