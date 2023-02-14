<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  courses: {},
  filters: Object,
});

let search = ref(props.filters.search);

watch(search, debounce(function (value) {
  router.get('/course', {search: value},{
    preserveState: true,
    replace: true
  });
}, 500));


</script>

<template>
  <Head title="Courses" />

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
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 mx-auto"
          >
            <div class="sm:flex flex items-center justify-between">
              <h2 class="text-h2">Courses</h2>
              <Link :href="route('course.create')" class="text-blue-500 text-p"
                >Add a Course</Link
              >
              <input
                v-model="search"
                type="text"
                placeholder="search..."
                class="rounded-xl px-2"
              />
            </div>
            <div class="flex flex-col mt-4">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                  class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
                >
                  <div
                    class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
                  >
                    <table class="min-w-full divide-y divide-gray-200">
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                          v-for="(course, key) in courses.data"
                          :key="course.id"
                        >
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  <Link
                                    :href="
                                      route('course.show', {
                                        course: course.id,
                                      })
                                    "
                                  >
                                    {{ course.name }}</Link
                                  >
                                </div>
                              </div>
                            </div>
                          </td>
                          <td
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                          >
                            <Link
                              :href="`/course/${course.id}/edit`"
                              class="text-blue-600 hover:text-blue-900"
                            >
                              Edit
                            </Link>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- Paginator -->
                  <Pagination :links="courses.links" class="mt-6" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
