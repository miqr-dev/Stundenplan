<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  location: {},
});

let form = useForm({
  location_id: props.location.id,
  name: "",
  room_number: "",
  floor: "",
  capacity: "",
});

let submit = () => {
  form.post("/room");
};
</script>

<template>
  <Head title="Room" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Add a new Room</h2>
              <Link :href="route('location.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
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
                  <SimpleInput
                    v-model="form.room_number"
                    label="Room Number"
                    type="text"
                    :error="form.errors.room_number"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.floor"
                    label="Floor"
                    type="text"
                    :error="form.errors.floor"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.capacity"
                    label="Capacity"
                    type="text"
                    :error="form.errors.capacity"
                  />
                </div>
                <div class="mb-1 text-right">
                  <SimpleSubmit />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
