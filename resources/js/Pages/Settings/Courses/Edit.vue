<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { ref } from "vue";

const props = defineProps({
  course: Object,
  templates: Array,
  cities: Array,
});

const form = useForm({
  id: props.course.id,
  name: props.course.name,
  type: props.course.type,
  lbrn: props.course.lbrn,
  city_id: props.course.city_id,
  template_id: props.course.template_id,
});

const selectedCity = ref(props.course.city_id);

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
              <Link :href="route('course.index')" class="text-blue-500 text-p"
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
                  <label for="city_id" class="block mb-2 text-xs font-bold text-gray-600 uppercase">City</label>
                  <select id="city_id" class="w-full p-2 border border-gray-400 bg-white rounded" v-model="form.city_id">
                    <option
                      v-for="city in cities"
                      :key="city.id"
                      :value="city.id"
                    >
                      {{ city.name }}
                    </option>
                  </select>
                  <span v-if="form.errors.city_id" class="error">{{
                    errors.city_id
                  }}</span>
                </div>
                <div class="mb-6">
                  <label for="template_id" class="block mb-2 text-xs font-bold text-gray-600 uppercase">Template</label>
                  <select id="template_id" class="w-full p-2 border border-gray-400 bg-white rounded" v-model="form.template_id">
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
