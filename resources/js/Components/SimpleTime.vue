<script setup>

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
});

</script>

<template>
  <label
    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
    for="label"
  >
    {{ label }}
  </label>
  <input
    class="w-full p-2 border border-gray-400 bg-white rounded"
    :class="{
      'cursor-not-allowed bg-gray-100': readonly,
    }"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    :readonly="readonly"
    type="time"
  />
  <div v-if="error" class="text-red-500 text-sm mt-1" v-text="error"></div>
</template>