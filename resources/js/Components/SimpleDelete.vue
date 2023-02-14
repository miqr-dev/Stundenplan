
<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  buttonText: {
    type: String,
    default: 'Delete',
  },
});

const emits = defineEmits(['click']);

const handleDelete = () => {
  if (confirm('Are you sure you want to delete this item?')) {
    emits('click');
  }
};
</script>



<template>
  <button class="text-orange-600 hover:text-orange-900" type="button" @click="handleDelete">
    {{ buttonText }}
  </button>
</template>

