<template>
  <div class="custom-table mb-2">
    <div class="py-2">
      <div class="flex justify-between">
        <div class="flex self-center">
          <button @click="showTable = !showTable" class="font-semibold leading-tight hover:underline text-2xl">
            {{ title }}
          </button>
          <span class="self-center ml-2 text-blue-500 text-md">({{ rows.length }})</span>
        </div>
        <a :href="createRoute" class="text-blue-500 text-p">{{ createLabel }}</a>
      </div>

      <div v-show="showTable" class="overflow-x-auto">
        <table class="min-w-full leading-normal table">
          <thead>
            <tr>
              <th v-for="header in headers" :key="header.key" class="border-gray-200 px-5 bg-gray-100 border-b-2 font-semibold py-3 text-gray-700 text-left text-xs tracking-wider uppercase">
                {{ header.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id" @click="onRowClick(row)" class="cursor-pointer hover:bg-gray-100">
              <td v-for="header in headers" :key="header.key" class="border-gray-200 px-5 bg-white border-b py-5 text-sm">
                {{ row[header.key] }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps({
  title: String,
  createRoute: String,
  createLabel: String,
  rows: Array,
  headers: Array,
  onRowClick: Function,
});

const showTable = ref(false);
</script>

<style scoped>
/* Custom styling */
</style>
