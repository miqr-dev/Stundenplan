<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { PencilIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
  cities: {},
});
</script>

<template>
  <Head title="Standorte" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="w-full mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>

    <div class="py-12">
      <div class="w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray w-full mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Standorte</h2>
              <SimpleBack />
            </div>

            <!-- Responsive Grid -->
            <div
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4"
            >
              <div class="p-5" v-for="(city, key) in cities" :key="city.id">
                <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
                  <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray">
                    <div class="flex justify-between">
                      <h2 class="text-h2">{{ city.name }}</h2>
                      <Link
                        :href="route('city.location.create', { city: city.id })"
                        class="text-blue-500 text-2xl"
                        >+</Link
                      >
                    </div>

                    <div class="flex flex-col mt-4">
                      <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                          <tr
                            v-for="(location, key) in city.locations"
                            :key="location.id"
                          >
                            <td
                              class="px-6 py-4 whitespace-nowrap flex justify-between"
                            >
                              <div class="text-sm font-medium text-gray-900">
                                <Link :href="`/location/${location.id}`">{{
                                  location.name
                                }}</Link>
                              </div>
                              <div class="flex items-center">
                                <Link
                                  :href="`/location/${location.id}/edit`"
                                  class="xl:hidden"
                                >
                                  <PencilIcon
                                    class="h-5 w-5 text-indigo-600 hover:text-indigo-900"
                                  />
                                </Link>
                                <Link
                                  :href="`/location/${location.id}/edit`"
                                  class="xl:inline hidden ml-4 text-sm text-indigo-600 hover:text-indigo-900"
                                  >Bearbeiten</Link
                                >
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
