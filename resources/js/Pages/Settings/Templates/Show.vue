<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, Head } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  template: {},
});
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
                {{ template.name }}
              </h2>
              <Link :href="route('template.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="mb-6">
                <label
                  class="block mb-2 text-xs font-bold text-gray-600 uppercase mt-4"
                >
                  Subjects:
                </label>
                <div>
                  <div
                    v-for="subject in template.subjects"
                    :key="subject.id"
                    class="flex justify-between"
                  >
                    <label>{{ subject.name }} </label>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      :fill="subject.color"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="w-6 h-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"
                      />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
