<script setup>
import { Link, Head, useForm } from "@inertiajs/vue3";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";
import { provide } from "vue";

provide("parentComponent", "Create");

const props = defineProps({
  subjects: {},
  cities: {},
});

const form = useForm({
  name: "",
  surname: "",
  maxhourweek: "",
  color: "",
  email: "",
  subjects: [],
  cities: [],
});
</script>

<template>
  <Head title="Teachers" />
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
              <h2 class="text-h2">Add a new Teacher</h2>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="form.post('/teacher')">
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
                    v-model="form.surname"
                    label="Surname"
                    type="text"
                    :error="form.errors.surname"
                  />
                </div>

                <div class="mb-6">
                  <SimpleInput
                    v-model="form.maxhourweek"
                    label="Max Hour / Week"
                    type="text"
                    :error="form.errors.maxhourweek"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.email"
                    label="email"
                    type="text"
                    :error="form.errors.email"
                  />
                </div>
                <!-- implement the vue-swatch here -->
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="maxhourweek"
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
                <!-- implement the vue-swatch here -->
                <SimpleItemSelector
                  v-model="form.subjects"
                  :items="props.subjects"
                  :searchable="true"
                  label="subject"
                ></SimpleItemSelector>
                <SimpleItemSelector
                  v-model="form.cities"
                  :items="props.cities"
                  :searchable="true"
                  label="city"
                ></SimpleItemSelector>
                <div class="mb-1 text-right">
                  <SimpleSubmit :processing="form.processing" Submit />
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