<script setup>
const props = defineProps({
  open: Boolean,
  title: String,
  message: String,
});

const emit = defineEmits(['confirm', 'cancel']);

const confirm = () => {
  emit('confirm');
};

const cancel = () => {
  emit('cancel');
};
</script>

<template>
  <transition
    enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 scale-75"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition ease-in duration-200 transform"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-75"
  >
    <div v-if="open" class="fixed z-10 inset-0 overflow-y-auto bg-black bg-opacity-50">
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="max-w-3xl mx-auto max-h-auto bg-white rounded-lg shadow-xl overflow-hidden transform transition-transform duration-300 scale-75 appear:scale-100">
          <div class="px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-700 mb-3">{{ title }}</h2>
            <p class="mt-1 text-xl text-gray-600 font-semibold overflow-hidden overflow-ellipsis max-h-[3.6rem]">{{ message }}</p>
          </div>
          <div class="px-6 py-3 bg-gray-100 flex justify-end space-x-4">
            <button @click="cancel" class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
            <button @click="confirm" class="px-4 py-2 font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>



