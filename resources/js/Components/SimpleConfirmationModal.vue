<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Are you sure?',
  },
  message: {
    type: String,
    default: '',
  },
});

const emits = defineEmits(['cancel', 'confirm']);

const cancel = () => {
  emits('cancel');
};

const confirm = (event) => {
  event.preventDefault();
  emits('confirm');
};
</script>

<template>
  <transition name="modal">
    <div v-if="isVisible" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-50" @click="cancel"></div>
      <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-6 mx-auto bg-white rounded-md shadow-lg transform transition-all duration-300">
          <div class="text-center mb-4">
            <h3 class="text-lg font-medium text-gray-700">{{ title }}</h3>
          </div>
          <div class="text-center">
            <p class="text-sm text-gray-500">{{ message }}</p>
          </div>
          <div class="mt-6 flex justify-end">
            <button @click.prevent="cancel" class="px-4 py-2 text-sm text-gray-500 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
            <button @click="confirm" class="ml-3 px-4 py-2 text-sm text-white bg-red-500 rounded-md hover:bg-red-600">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>


<style scoped>
.modal-enter {
  opacity: 0;
  transform: scale(0.9);
}
.modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s;
}
</style>