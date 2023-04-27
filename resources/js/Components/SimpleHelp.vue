<template>
  <div class="relative inline-flex items-center">
    <span @mouseenter="showHelpText" @mouseleave="hideHelpText" class="focus:outline-none">
      <QuestionMarkCircleIcon class="w-6 h-6 text-violet-500 hover:text-violet-700" />
    </span>
    <div v-if="showText" class="absolute left-full ml-2 p-2 text-xs bg-white border border-gray-200 rounded shadow z-10 w-96 h-auto overflow-y-auto">
      {{ text }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";

defineProps({
  text: {
    type: String,
    default: "",
  },
});

const showText = ref(false);

function showHelpText() {
  showText.value = true;
}

function hideHelpText() {
  showText.value = false;
}
</script>