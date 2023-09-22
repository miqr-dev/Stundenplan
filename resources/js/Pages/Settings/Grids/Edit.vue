<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  grid: Object,
});

const form = useForm({
  id: props.grid.id,
  name: props.grid.name,
  description: props.grid.description
});

const update = () => {
  form.patch(`/grid/${props.grid.id}`);
};

const destroy = () => {
  form.delete(`/grid/${props.grid.id}`);
};
</script>

<template>
  <Head title="Stundenraster" />

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
              <h2 class="text-h2">Stundenraster bearbeiten</h2>
              <SimpleBack />
            </div>
            <div class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
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
                  <SimpleInput
                    v-model="form.description"
                    label="Beschreibung"
                    type="text"
                    :error="form.errors.description"
                  />
                </div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Stundenraster löschen" />
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
