<script setup>
import { Head, Link } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  cities: {},
});
</script>

<template>
  <Head title="Städte" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 mx-auto">
            <div class="sm:flex justify-between">
              <h2 class="text-h2">Städte</h2>
              <Link :href="route('city.create')" class="text-blue-500 text-p">Stadt hinzufügen</Link>
            </div>
            <div class="flex flex-col mt-4">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(city, key) in cities" :key="city.id">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  <Link method="get" :href="`/city/${city.id}`">{{ city.name }}</Link>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link :href="`/city/${city.id}/edit`" class="text-blue-600 hover:text-blue-900">Bearbeiten</Link>
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
  </BreezeAuthenticatedLayout>
</template>

