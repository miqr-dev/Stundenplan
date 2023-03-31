<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/vue3";
import SimpleBox from "@/Components/SimpleBox.vue";
import { ref } from 'vue';


const items = [
  { id: 1, name: "Item 1" },
  { id: 2, name: "Item 2" },
  { id: 3, name: "Item 3" }
].sort((a, b) => a.name.localeCompare(b.name));
const selectedItems = ref([]);

// function toggleItem(item) {
//   if (!props.selectedItems) {
//     props.selectedItems = [];
//   }
//   const index = props.selectedItems.indexOf(item);
//   if (index === -1) {
//     availableItems.value.splice(availableItems.value.indexOf(item), 1);
//     props.selectedItems.push(item);
//   } else {
//     props.selectedItems.splice(index, 1);
//     availableItems.value.push(item);
//   }
// }
</script>

<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        select list
      </h2>
      <div class="h-full grid place-items-center bg-gray-800 text-white"></div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
<SimpleBox :items="items" :selectedItems="selectedItems" />
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
