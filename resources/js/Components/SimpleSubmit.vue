<script setup>
import {computed} from 'vue';
const props = defineProps({
  processing: {
      type: Boolean,
      default: false,
    },
});
    const isProcessing =  computed(() => props.processing)

</script>

<template>
  <button
    type="submit"
    class="text-blue-500 hover:text-blue-700"
    :class="{
      'cursor-not-allowed': isProcessing,
    }"
    :disabled="isProcessing"
  >
    <slot>Einreichen</slot>
  </button>
</template>
