<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";

const props = defineProps({
  teachers: {},
  filters: Object,
  cities: Array,
  defaultCityId: Number,  
});

let search = ref(props.filters.search);

watch(
  search,
  debounce(function (value) {
    let params = { search: value };
    // If selectedCityId is not null, include it in the parameters
    if (selectedCityId.value !== null) {
      params.cityId = selectedCityId.value;
    }
    router.get(
      "/teacher",
      params,
      {
        preserveState: true,
        replace: true,
      }
    );
  }, 500)
);

function viewAllTeachers() {
  let params = { viewAll: true };
  // Remove search when viewing all
  if (router.search) {
    delete router.search;
    search.value = ""; // Clear the search box
  }
  router.get("/teacher", params, {
    preserveState: true,
    replace: true,
  });
}

const selectedCityId = ref(props.filters.cityId || Number(props.defaultCityId) || null);

function filterByCity(cityId) {
  selectedCityId.value = cityId;  // Corrected this line
  let params = { ...router.query, cityId }; // merge existing query parameters
  // Don't delete params.search if you want to preserve it
  router.get("/teacher", params, {
    preserveState: true,
    replace: true,
  });
}
</script>

<template>
  <Head title="Teachers" />

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
            <h2 class="text-h2 mb-2">Teachers</h2>

            <!-- <Link @click="viewAllTeachers" class="text-blue-500 text-p"
                >View All</Link
              > -->
            <!-- Horizontal list of cities -->
        <!-- Modified the Horizontal list of cities here -->
            <div class="city-filters flex justify-between text-xs mb-4 w-full">
              <span
                v-for="city in cities"
                :key="city.id"
                @click="filterByCity(city.id)"
                :class="{
                  'bg-gray-600 text-white': selectedCityId === city.id,
                  'px-4 py-1 cursor-pointer hover:bg-gray-200 rounded-xl': true,
                }"
              >
                {{ city.name }}
              </span>
            </div>
            <div class="sm:flex flex items-center justify-between">
              <Link :href="route('teacher.create')" class="text-blue-500 text-p"
                >Add a Teacher</Link
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
                          v-for="(teacher, key) in teachers.data"
                          :key="teacher.id"
                        >
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  <Link
                                    :href="
                                      route('teacher.show', {
                                        teacher: teacher.id,
                                      })
                                    "
                                  >
                                    {{ teacher.name }}&#44; &nbsp;{{
                                      teacher.surname
                                    }}</Link
                                  >
                                </div>
                              </div>
                            </div>
                          </td>
                          <td
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                          >
                            <Link
                              :href="`/teacher/${teacher.id}/edit`"
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
                  <Pagination :links="teachers.links" class="mt-6" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
