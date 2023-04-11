<template>
  <tr>
        <td
      v-for="(column, index) in columns"
      :key="column.header + '-' + index"
      class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
    >
      <template v-if="column.type === 'text'">
        <p class="text-gray-900 whitespace-no-wrap">{{ column.value }}</p>
      </template>
      <template v-else-if="column.type === 'swatches' && showSwatches">
        <VSwatches
          v-model="column.value"
          swatches="text-advanced"
          row-length="12"
          show-border
          popover-x="button"
          :disabled="true"
        ></VSwatches>
      </template>
    </td>
    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
      <button
        type="button"
        class="inline-block text-gray-500 hover:text-gray-700"
      >
        <svg
          class="inline-block h-6 w-6 fill-current"
          viewBox="0 0 24 24"
        >
          <path
            d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
          />
        </svg>
      </button>
    </td>
  </tr>
</template>

<script setup>
import { VSwatches } from "vue3-swatches";
  const props =  defineProps ({
     item: {
      type: Object,
      required: true,
    },
    showSwatches: {
      type: Boolean,
      default: true,
    },
    columns: {
      type: Array,
      required: true,
    },
  });
</script>
