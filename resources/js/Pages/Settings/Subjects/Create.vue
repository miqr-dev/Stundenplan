<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, router, Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import 'vue3-swatches/dist/style.css'

const props = defineProps({
  templates: {},
});

const form = useForm({
  name: "",
  color: "",
  templates: [],
});

</script>

<template>
  <Head title="Subjects" />
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
              <h2 class="text-h2">Add a new Subject</h2>
              <Link :href="route('subject.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="form.post('/subject')">
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
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="maxhourweek"
                  >
                    Color
                  </label>
                    <VSwatches v-model="form.color" class="m-0 p-0"/>
                  <div
                    v-if="form.errors.color"
                    v-text="form.errors.color"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="subject"
                  >
                    Templates
                  </label>
                  <div v-for="template in templates" :key="template.id">
                    <label
                      >{{ template.name }}
                      <input
                        type="checkbox"
                        :value="template.id"
                        v-model="form.templates"
                      />
                    </label>
                  </div>
                  <div
                    v-if="form.errors.templates"
                    v-text="form.errors.templates"
                    class="text-red-500 text-sm mt-1"
                  ></div>
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
