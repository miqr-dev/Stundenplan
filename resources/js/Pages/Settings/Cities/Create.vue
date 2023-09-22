<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  bundesland: Array,
});

let form = useForm({
  name: "",
  bundesland_id: "",
});

let submit = () => {
  form.post("/city");
};
</script>

<template>
  <Head title="Städte" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-screen-xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-full sm:w-3/4 md:w-2/3 lg:w-3/5 xl:w-1/2 shadow-md mx-auto">
            <div class="flex justify-between">
              <h2 class="text-h2">Neue Stadt hinzufügen</h2>
              <SimpleBack />
            </div>
            <div class="bg-gray-100g p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
              <form @submit.prevent="submit">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="mb-6">
                  <label class="block mb-2 text-xs font-bold text-gray-600 uppercase" for="bundesland">
                    Bundesland
                  </label>
                  <select
                    v-model="form.bundesland_id"
                    class="w-full p-2 border border-gray-400 rounded"
                    id="bundesland"
                    name="bundesland"
                  >
                    <option value="">Bitte wählen Sie ein Bundesland aus</option>
                    <option
                      v-for="land in props.bundesland"
                      :value="land.id"
                    >
                      {{ land.name }}
                    </option>
                  </select>
                  <div
                    v-if="form.errors.bundesland_id"
                    v-text="form.errors.bundesland_id"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>

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
