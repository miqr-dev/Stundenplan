<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/vue3'
import SettingsSubMenu from '@/Components/SettingsSubMenu.vue';

</script>

<template>
  <Head title="Einstellungen" />
    <BreezeAuthenticatedLayout>
      <header class="bg-gray-100 shadow">
        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
          <SettingsSubMenu></SettingsSubMenu>
        </div>
      </header>
      <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 bg-white border-b border-gray-200">
                    In this Page you can add, remove and change all the options listed in the Menu above
                  </div>
              </div>
          </div>
      </div>
    </BreezeAuthenticatedLayout>
</template>
