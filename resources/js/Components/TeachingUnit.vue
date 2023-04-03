<template>
  <div>
    <div v-if="!showModal">
      <div v-if="selectedOption1">{{ selectedOption1.name }}</div>
      <div v-if="selectedOption2">
        {{ selectedOption2.surname }}, {{ selectedOption2.name }}
      </div>
      <div v-if="selectedOption3">{{ selectedOption3 }}</div>
      <div v-if="selectedOption4">{{ selectedOption4 }}</div>
      <button
        @click="showModal = true"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      >
        Edit
      </button>
    </div>
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    >
      <div class="bg-white p-6 rounded shadow-md">
        <h3 class="text-xl font-semibold mb-4">
          {{ day }} - {{ date }}
          {{ moment(gridSlotItem.start_time, "HH:mm:ss").format("HH.mm") }}
          -
          {{ moment(gridSlotItem.end_time, "HH:mm:ss").format("HH:mm") }}
        </h3>
        <select
          v-model="selectedOption1"
          @change="emitSelection"
          :style="selectedOption1Style"
        >
          <option disabled value="">Select Option 1</option>
          <option v-for="option in options1" :value="option">
            {{ option.name }}
          </option>
        </select>

        <select
          v-model="selectedOption2"
          @change="emitSelection"
          v-if="selectedOption1"
        >
          <option disabled value="">Select Option 2</option>
          <option v-for="option in selectedOption1.teachers" :value="option">
            {{ option.surname }}, {{ option.name }}
          </option>
        </select>

        <select v-model="selectedOption3" @change="emitSelection">
          <option disabled value="">Select Option 3</option>
          <option v-for="option in options3" :value="option">
            {{ option }}
          </option>
        </select>

        <select v-model="selectedOption4" @change="emitSelection">
          <option disabled value="">Select Option 4</option>
          <option v-for="option in options4" :value="option">
            {{ option }}
          </option>
        </select>
        <button
          @click="showModal = false"
          class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineEmits } from "vue";
import moment from "moment";

const props = defineProps({
  options1: {
    type: Array,
    required: true,
  },
  options2: {
    type: Array,
  },
  day: {
    type: String,
    required: true,
  },
  gridSlotItem: {
    type: Object,
    required: true,
  },
  date: {
    type: Object,
  },
});

const emit = defineEmits({ selection: null });

const selectedOption1 = ref("");
const selectedOption2 = ref("");
const selectedOption3 = ref("");
const selectedOption4 = ref("");
const showDropdown = ref(false);
const showModal = ref(false);


const selectedOption1Style = computed(() => {
  return selectedOption1.value
    ? { backgroundColor: selectedOption1.value.color, color: "white" }
    : {};
});

const selectSlot = () => {
  if (
    selectedOption1.value &&
    selectedOption2.value &&
    selectedOption3.value &&
    selectedOption4.value
  ) {
    emitSelection();
  }
};

const emitSelection = () => {
  emit("selection", {
    date: props.date,
    gridSlotItem: props.gridSlotItem,
    selectedOptions: [
      selectedOption1.value,
      selectedOption2.value,
      selectedOption3.value,
      selectedOption4.value,
    ],
  });
};
</script>