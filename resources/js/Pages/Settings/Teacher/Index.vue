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
    if (selectedCityId.value !== null) {
      params.cityId = selectedCityId.value;
    }
    router.get("/teacher", params, {
      preserveState: true,
      replace: true,
    });
  }, 500)
);

const selectedCityId = ref(
  props.filters.cityId || Number(props.defaultCityId) || null
);

function filterByCity(cityId) {
  selectedCityId.value = cityId;
  let params = { ...router.query, cityId };
  router.get("/teacher", params, {
    preserveState: true,
    replace: true,
  });
}
</script>

<template>
  <Head title="Dozenten" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-screen-xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-full sm:w-3/4 md:w-2/3 lg:w-3/5 xl:w-1/2 mx-auto">
            <!-- Title -->
            <h2 class="text-h2">Dozenten</h2>
            <!-- Add Button and Search Box -->
            <div class="sm:flex justify-between items-center mt-4">
              <Link
                :href="route('teacher.create')"
                class="text-blue-500 text-p"
              >
                Dozent/in hinzufügen
              </Link>
              <div>
                <input
                  v-model="search"
                  type="text"
                  placeholder="Suche..."
                  class="rounded-xl px-2"
                />
              </div>
            </div>

            <!-- City Filters -->
            <div class="city-filters flex justify-between text-xs mt-4">
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
            <!-- Table and content below -->
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
                                    {{ teacher.name }}, &nbsp;{{
                                      teacher.surname
                                    }}
                                  </Link>
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
                              Bearbeiten
                            </Link>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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
