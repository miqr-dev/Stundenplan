<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { PencilIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
  location: {},
});
</script>

<template>
  <Head title="Räume" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-screen-xl mx-auto py-1 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <!-- Changed to w-full for full width -->
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-full shadow-md">
            <div class="sm:flex justify-between">
              <h2 class="text-h2">{{ location.name }}</h2>
              <Link :href="route('room.create', {location: location.id})" class="text-blue-500 text-p"> Raum hinzufügen</Link>
              <SimpleBack />
            </div>
            <div class="flex flex-col mt-4">
              <div class="-my-2 sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                      <!-- Table content here -->
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(room, key) in location.rooms" :key="room.id">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  <Link method="get" :href="`/room/${room.id}`">{{ room.name }}</Link>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  {{ room.room_number }}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  {{ room.floor }}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div>
                                <div class="text-sm font-medium text-gray-900">
                                  {{ room.capacity }}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <Link :href="`/room/${room.id}/edit`" class="text-blue-600 hover:text-blue-900">
                              <PencilIcon class="h-5 w-5" />
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
  </BreezeAuthenticatedLayout>
</template>
