<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  location: Object,
});

let form = useForm({
  name: props.location.name,
  id: props.location.id,
});

let update = () => {
  form.patch(`/location/${props.location.id}`);
};

let destroy = () => {
  form.delete(`/location/${props.location.id}`);
};
</script>

<template>
  <Head title="Cities" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Edit the location name</h2>
              <Link :href="route('location.index')" class="text-blue-500 text-p"
                >X</Link
              >
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
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Delete Location" />
                  <SimpleSubmit :processing="form.processing"/>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
