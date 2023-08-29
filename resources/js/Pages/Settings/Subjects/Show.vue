<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, Head, useForm, router } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";

const props = defineProps({
  subject: {},
  teachers: {},
});

const subject = props.subject;

const submit = async (id) => {
  await router.delete(route("subject.destroy", { subject: id }));
  router.push(route("subject.index"));
};
</script>

<template>
  <Head title="Subject Show" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2>
                {{ subject.name }}
              </h2>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="flex space-x-10">
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="maxhourweek"
                  >
                    Color
                  </label>
                  <VSwatches
                    v-model="subject.color"
                    swatches="text-advanced"
                    row-length="12"
                    show-border
                    popover-x="button"
                    :disabled="true"
                  ></VSwatches>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="maxhourweek"
                  >
                    Soll Stunden
                  </label>
                  <p>{{ subject.default_soll }} h</p>
                </div>
              </div>
              <div class="mb-6">
                <label
                  class="block mb-2 text-xs font-bold text-gray-600 uppercase mt-4"
                >
                  Templates:
                </label>
                <div v-if="subject.templates.length">
                  <div v-for="template in subject.templates" :key="template.id">
                    <label>{{ template.name }} </label>
                  </div>
                </div>
                <div v-else>
                  <p>this Subject is not yet in any Template</p>
                </div>
              </div>
              <div class="mb-6">
                <label
                  class="font-bold block mb-2 text-gray-600 text-xs uppercase mt-4"
                  >Teachers:</label
                >
                <div v-if="teachers.length">
                  <div
                    :key="teacher.id"
                    v-for="teacher in teachers"
                    class="flex items-center space-x-4"
                  >
                    <Link
                      :href="route('teacher.edit', { teacher: teacher.id })"
                      class="hover:underline"
                    >
                      {{ teacher.name }} {{ teacher.surname }}
                    </Link>
                    <div
                      :style="{ backgroundColor: teacher.color }"
                      class="w-4 h-4"
                    ></div>
                  </div>
                </div>
                <div v-else>
                  <p>No teachers available for this subject in your city.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
