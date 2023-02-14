<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  room: Object,
});

let form = useForm({
  name: props.room.name,
  room_number: props.room.room_number,
  floor: props.room.floor,
  capacity: props.room.capacity,
  id: props.room.id,
});

let update = () => {
  form.patch(`/room/${props.room.id}`);
};

let destroy = () => {
  form.delete(`/room/${props.room.id}`);
};
</script>

<template>
  <Head title="Rooms" />

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
              <h2 class="text-h2">Edit the room name</h2>
              <a
                href="#"
                class="text-blue-500 text-p"
                onclick="history.back();return false;"
                >X</a
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="update">
                <div class="m-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="m-6">
                  <SimpleInput
                    v-model="form.room_number"
                    label="Room Number"
                    type="text"
                    :error="form.errors.room_number"
                  />
                </div>
                <div class="m-6">
                  <SimpleInput
                    v-model="form.floor"
                    label="Floor"
                    type="text"
                    :error="form.errors.floor"
                  />
                </div>
                <div class="m-6">
                  <SimpleInput
                    v-model="form.capacity"
                    label="Capacity"
                    type="text"
                    :error="form.errors.capacity"
                  />
                </div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Delete Room" />
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
