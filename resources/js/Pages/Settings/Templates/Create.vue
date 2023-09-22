<script setup>
import { Link, Head, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";
import { provide } from "vue";

provide("parentComponent", "Create");
const props = defineProps({
  subjects: {},
});

const form = useForm({
  name: "",
  subjects: [],
});
</script>

<template>
  <Head title="Vorlagen" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto">
            <h2 class="text-h2 mb-4">Eine neue Vorlage hinzufügen</h2>
            <div class="flex justify-between">
              <SimpleBack />
            </div>
            <div class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
              <form @submit.prevent="form.post('/template')">
                <div class="mb-6">
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
                  label="subject"
                  displayLabel="Fach/Fächer"
                ></SimpleItemSelector>
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
