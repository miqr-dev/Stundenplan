<script>
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    id: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    value: {
      type: [String, Number],
      required: true
    },
    options: {
      type: Array,
      required: true
    },
    error: {
      type: String,
      default: ''
    }
  }
})
</script>


<template>
  <div>
    <label :for="id" class="block mb-2 text-xs font-bold text-gray-600 uppercase">
      {{ label }}
    </label>
    <select :id="id" class="w-full p-2 border border-gray-400 bg-white rounded" v-model="value">
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <span v-if="error" class="error">{{ error }}</span>
  </div>
</template>



