<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  subjects: Object,
  filters: Object,
});

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
  router.get('/subject', {search: value},{
    preserveState: true,
    replace: true
  });
}, 500));
</script>

<template>
  <Head title="Fächer" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full lg:w-1/2 md:w-3/4 sm:w-full w-full mx-auto">
            <h2 class="text-h2 mb-4">Fächer</h2>
            <div class="flex justify-between">
              <Link :href="route('subject.create')" class="text-blue-500 text-p">Neues Fach hinzufügen</Link>
              <input v-model="search" type="text" placeholder="Suchen..." class="rounded-xl px-2">
            </div>
            <div class="flex flex-col mt-4">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(subject, key) in subjects.data" :key="subject.id">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  <Link :href="route('subject.show', { subject: subject.id })">
                                    {{ subject.name }}</Link>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link :href="`/subject/${subject.id}/edit`" class="text-blue-600 hover:text-blue-900">
                              Bearbeiten
                            </Link>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- Paginator -->
                  <Pagination :links="subjects.links" class="mt-6"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
