<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/vue3';
import SettingsSubMenu from '@/Components/SettingsSubMenu.vue';
import { Link } from '@inertiajs/vue3';

let form = useForm({
  name: '',
});

let submit = () => {
  form.post('/location');
};

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
                    <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto">
                      <div class="flex justify-between">
                        <h2 class="text-h2">Add a new Location</h2>
                        <Link :href="route('location.index')" class="text-blue-500 text-p">X</Link>
                      </div>
                        <div class="bg-gray-200 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
                          <form @submit.prevent="submit"> 
                            <div class="mb-6">
                                <label class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                                  for="name">
                                    Name
                                </label>
                                <input 
                                v-model="form.name"
                                  class="w-full p-2 border border-gray-400 rounded"
                                  type="text"
                                  id="name"
                                  name="name"
                                  >
                                  <div v-if="form.errors.name" v-text="form.errors.name" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div class="mb-1">
                              <button type="submit"
                              class="bg-gray-400 text-white rounded py-2 px-2 hover:bg-gray-500"
                              :class="{'cursor-not-allowed' : form.processing  }"
                              :disabled="form.processing"
                              >
                              Submit
                              </button>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
