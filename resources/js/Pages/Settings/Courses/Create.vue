<script setup>
import { Link, Head, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";



const props = defineProps({
  templates: {},
  cities: [],
});

const form = useForm({
  name: "",
  lbrn: "",
  type: "",
  city_id: "",
  template_id: "",
});
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
          <div class="
              p-6
              bg-gray-100
              text-h2
              font-bold
              text-p-gray
              h-full
              w-1/2
              shadow-md
              mx-auto
            ">
            <div class="flex justify-between">
              <h2 class="text-h2">Add a new Course</h2>
              <Link :href="route('course.index')" class="text-blue-500 text-p">X</Link>
            </div>
            <div class="
                bg-gray-100
                p-5
                rounded-xl
                mx-auto
                text-p
                font-bold
                space-y-2
                mt-5
                shadow-sm
                sm:rounded-lg
              ">
              <form @submit.prevent="form.post('/course')">
                <div class="mb-6">
                  <SimpleInput v-model="form.name" label="Name" type="text" :error="form.errors.name" />
                </div>
                <div class="mb-6">
                  <SimpleInput v-model="form.lbrn" label="lbrn" type="text" :error="form.errors.lbrn" />
                </div>
                <div class="mb-6">
                  <SimpleInput v-model="form.type" label="type" type="text" :error="form.errors.type" />
                </div>
                <div class="mb-6">
                  <label class="block mb-2 text-xs font-bold text-gray-600 uppercase" for="template">
                    Template
                  </label>
                  <select v-model="form.template_id" class="w-full p-2 border border-gray-400 rounded" id="template"
                    name="template">
                    <option value="">Please select a Template</option>
                    <option v-for="template in props.templates" :value="template.id">
                      {{ template.name }}
                    </option>
                  </select>
                  <div v-if="form.errors.template" v-text="form.errors.template" class="text-red-500 text-sm mt-1">
                  </div>
                </div>
                <div class="mb-6">
                  <label class="block mb-2 text-xs font-bold text-gray-600 uppercase" for="city">
                    City
                  </label>
                  <select v-model="form.city_id" class="
                      w-full
                      p-2
                      border border-gray-400
                      rounded
                      hover:border-gray-500
                    " id="city" name="city">
                    <option value="" class="hover:bg-gray-200 hover:text-black">
                      Please select
                    </option>
                    <option v-for="city in props.cities" :value="city.id"
                      class="hover:bg-gray-200 hover:text-black hover:font-bold">
                      {{ city.name }}
                    </option>
                  </select>
                  <div v-if="form.errors.city" v-text="form.errors.city" class="text-red-500 text-sm mt-1"></div>
                </div>
                <div class="mb-1 text-right">
                  <SimpleSubmit :processing="form.processing"
                    Submit
                  />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
