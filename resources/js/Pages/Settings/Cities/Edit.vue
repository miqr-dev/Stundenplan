<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  city: Object,
  bundesland: Array,
});

const form = useForm({
  name: props.city.name,
  id: props.city.id,
  bundesland_id: props.city.bundesland_id,
});

const update = () => {
  form.patch(route("city.update", form.id));
};

const destroy = () => {
  form.delete(route("city.destroy", form.id));
};
</script>

<template>
  <Head title="Städte Bearbeiten" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-screen-xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-full sm:w-3/4 md:w-2/3 lg:w-3/5 xl:w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Stadtname bearbeiten</h2>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="update">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="mb-6">
                  <label
                    for="bundesland_id"
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  >
                    Bundesland
                  </label>
                  <select
                    id="bundesland_id"
                    class="w-full p-2 border border-gray-400 bg-white rounded"
                    v-model="form.bundesland_id"
                  >
                    <option
                      v-for="land in bundesland"
                      :key="land.id"
                      :value="land.id"
                    >
                      {{ land.name }}
                    </option>
                  </select>
                  <span
                    v-if="form.errors.bundesland_id"
                    class="text-red-500 text-sm mt-1"
                  >
                    {{ form.errors.bundesland_id }}
                  </span>
                </div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy()" buttonText="Stadt löschen" />
                  <!-- <button type="button" @click="destroy">Delete</button> -->
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
