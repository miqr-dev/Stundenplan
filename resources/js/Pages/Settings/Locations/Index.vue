<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/vue3'
import SettingsSubMenu from '@/Components/SettingsSubMenu.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  locations: {}
});
</script>

<template>
    <Head title="Locations" />

    <BreezeAuthenticatedLayout>
      <header class="bg-gray-100 shadow">
        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
          <SettingsSubMenu></SettingsSubMenu>
        </div>
      </header>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
                    <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full mx-auto">
                      <div class="flex justify-between">
                        <h2 class="text-h2">Locations</h2>
                        <!-- <Link :href="route('location.create')" class="text-blue-500 text-p"></Link> -->
                      </div>

                        <div class="grid grid-cols-3 gap-1">
                          <div class="p-5" v-for="(location, key) in locations" :key="location.id">                             
                            <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
                              <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full min-w-full mx-auto">
                                <div class="flex justify-between">
                                  <h2 class="text-h2">{{ location.name }}</h2>
                                  <Link :href="route(`location.create`)"  class="text-blue-500 text-2xl">+</Link>
                                </div>
                                <div class="flex flex-col mt-4">
                                  <table class="min-w-full divide-y divide-gray-200">
                                    <tbody class="bg-white divide-y divide-gray-200">
                                      <tr v-for="(address, key) in location.locations" :key="address.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                          <div class="flex items-center">
                                            <div>
                                              <div class="text-sm font-medium text-gray-900">
                                               <Link :href="`/location/${address.id}`"> {{ address.name }}</Link>
                                              </div>
                                            </div>
                                          </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                          <Link :href="`/location/${address.id}/edit`" class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                          </Link>
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
