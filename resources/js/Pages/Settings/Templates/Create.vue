<template>
<Head title="Create Template" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
 <form class="bg-white p-6 rounded-lg shadow-md" @submit.prevent="submit">
    <label class="block font-medium text-gray-700 mb-2">
      Name:
      <input class="form-input rounded-md py-2 px-3 text-gray-900 leading-5" v-model="form.name" type="text" />
    </label>
    <br />
    <label class="block font-medium text-gray-700 mb-2">
      Subjects:
      <select class="form-select rounded-md py-2 px-3 text-gray-900 leading-5" v-model="form.subjects" multiple>
        <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
      </select>
    </label>
    <br />
    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg" type="submit">Save</button>
    <Link :href="route('template.index')" class="text-indigo-500 hover:text-indigo-700">Cancel</Link>
  </form>
  </BreezeAuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  subjects: {},
});

const form = reactive({
  name: '',
  subjects: [],
})

const computed = {
  subjects() {
    return Object.assign({}, route('template.create').props.subjects)
  }
}

const submit = async () => {
  await router.post(route('template.store'), form)
}
</script>