<script>
export default {
  inheritAttrs: false,
};
</script>

<script setup>
import SimpleHelp from "@/Components/SimpleHelp.vue";
defineProps({
  label: {
    type: String,
    default: "",
  },
  modelValue: {
    type: [String, Number],
    default: "",
  },
  error: {
    type: String,
    default: "",
  },
  readonly: {
    type: Boolean,
    default: false,
  },
  helpText: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <div class="flex flex-col">
    <div class="flex items-center mb-2">
      <label
        class="inline-flex items-center text-xs font-bold text-gray-600"
        for="label"
      >
        <span class="uppercase">{{ label }}</span>
        <SimpleHelp v-if="helpText" :text="helpText" class="ml-1"></SimpleHelp>
      </label>
    </div>
    <input
      class="w-full p-2 border border-gray-400 bg-white rounded"
      :class="{
        'cursor-not-allowed bg-gray-100': readonly,
      }"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :readonly="readonly"
    />
    <div v-if="error" class="text-red-500 text-sm mt-1" v-text="error"></div>
  </div>
</template>