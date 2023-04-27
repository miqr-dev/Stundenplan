<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { ref } from "vue";

const props = defineProps({
  course: Object,
  templates: Array,
  locations: Array,
  rooms: Array,
});

const form = useForm({
  id: props.course.id,
  name: props.course.name,
  type: props.course.type,
  lbrn: props.course.lbrn,
  room_id: props.course.room_id,
  template_id: props.course.template_id,
});

const selectedCity = ref(props.course.location_id);

const update = () => {
  form.patch(route("course.update", form.id));
};

const destroy = () => {
  form.delete(route("course.destroy", form.id));
};
</script>

<template>
  <Head title="Course" />
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
              <h2 class="text-h2">Edit {{ form.name }}</h2>
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
                  <SimpleInput
                    v-model="form.lbrn"
                    label="lbrn"
                    type="text"
                    :error="form.errors.lbrn"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.type"
                    label="type"
                    type="text"
                    :error="form.errors.type"
                  />
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="room_id"
                  >
                    Room
                  </label>
                  <select
                    id="room_id"
                    class="w-full p-2 border border-gray-400 bg-white rounded"
                    v-model="form.room_id"
                  >
                    <option value="" class="hover:bg-gray-200 hover:text-black">
                      Please select
                    </option>
                    <optgroup
                      v-for="location in props.locations"
                      :key="location.id"
                      :label="location.name"
                    >
                      <option
                        v-for="room in props.rooms.filter(
                          (room) => room.location_id === location.id
                        )"
                        :key="room.id"
                        :value="room.id"
                        class="hover:bg-gray-200 hover:text-black hover:font-bold"
                      >
                        {{ room.name }} {{ room.room_number }}
                      </option>
                    </optgroup>
                  </select>
                  <span v-if="form.errors.room_id" class="error">{{
                    errors.room_id
                  }}</span>
                </div>
                <div class="mb-6">
                  <label
                    for="template_id"
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    >Template</label
                  >
                  <select
                    id="template_id"
                    class="w-full p-2 border border-gray-400 bg-white rounded"
                    v-model="form.template_id"
                  >
                    <option
                      v-for="template in templates"
                      :key="template.id"
                      :value="template.id"
                    >
                      {{ template.name }}
                    </option>
                  </select>
                  <span v-if="form.errors.template_id" class="error">{{
                    errors.template_id
                  }}</span>
                </div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Delete Course" />
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
