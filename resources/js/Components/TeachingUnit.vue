<script setup>
import { ref, computed, defineEmits, onMounted, watch } from "vue";
import moment from "moment";
import { router } from '@inertiajs/vue3';
import axios from 'axios';


const props = defineProps({
  options1: {
    type: Array,
    required: true,
  },
  options2: {
    type: Array,
  },
  options3: {
    type: Array,
    required: true,
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
    courseId: {
    type: Number,
    required: true,
  },
  calendarWeek: {
    type: Number,
    required: true,
  },
});

// onMounted(() => {
//   router.visit('/stundenplan/checkTeachingUnit', {
//   method: 'get',
//   data: {
//     course_id: props.courseId,
//     week: props.calendarWeek,
//     date: props.date.format("YYYY-MM-DD"), // make sure the date is formatted correctly
//     start_time: props.gridSlotItem.start_time,
//     end_time: props.gridSlotItem.end_time},
//   });
// });

onMounted(() => {
  checkTeachingUnit();
});

const checkTeachingUnit = async () => {
  const { data } = await axios.post("/stundenplan/check-teaching-unit", {
    week: props.calendarWeek,
    course_id: props.courseId,
    start_time: props.gridSlotItem.start_time,
    end_time: props.gridSlotItem.end_time,
    date: props.date.format("YYYY-MM-DD"),
  });

  if (data.dataExists) {
    console.log(data.dataExists);
  }
};

const closeAndEmit = () => {
  emitSelection();
  showModal.value = false;
};

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

const selectedRoom = computed(() => {
  return props.options3.find((room) => room.id === selectedOption3.value);
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

const groupedRooms = computed(() => {
  return props.options3.reduce((result, room) => {
    const locationName = room.location.name;
    if (result[locationName]) {
      result[locationName].push(room);
    } else {
      result[locationName] = [room];
    }
    return result;
  }, {});
});
</script>

<template>
  <div>
    <div v-if="!showModal">
      <div v-if="selectedOption1">{{ selectedOption1.name }}</div>
      <div v-if="selectedOption2">
        {{ selectedOption2.surname }}, {{ selectedOption2.name }}
      </div>
      <div v-if="selectedRoom">
        {{ selectedRoom.name }} - {{ selectedRoom.room_number }}
      </div>

      <!-- Display existing teaching units if they exist -->
      <!-- <div v-if="teachingUnits && teachingUnits.length">
        <h3>Existing Teaching Units</h3>
        <ul>
          <li v-for="unit in teachingUnits" :key="unit.id">
            {{ unit.subject.name }} by {{ unit.teacher.surname }}, {{ unit.teacher.name }} in room {{ unit.room.name }} - {{ unit.room.room_number }}
          </li>
        </ul>
      </div> -->

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
      <div class="bg-white p-6 rounded shadow-md relative">
        <button
          @click.prevent="showModal = false"
          class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl font-semibold"
        >
          &times;
        </button>
        <h3 class="text-xl font-semibold mb-4">
          {{ day }} &nbsp; {{ moment(date).format("DD.MMMM") }} &nbsp;

          {{ moment(gridSlotItem.start_time, "HH:mm:ss").format("HH.mm") }}
          -
          {{ moment(gridSlotItem.end_time, "HH:mm:ss").format("HH:mm") }}
        </h3>

        <div class="space-y-4 flex flex-col">
          <select v-model="selectedOption1" :style="selectedOption1Style">
            <option disabled value="">Select Option 1</option>
            <option v-for="option in options1" :value="option">
              {{ option.name }}
            </option>
          </select>

          <select v-model="selectedOption2" v-if="selectedOption1">
            <option disabled value="">Select Option 2</option>
            <option v-for="option in selectedOption1.teachers" :value="option">
              {{ option.surname }}, {{ option.name }}
            </option>
          </select>

          <select v-model="selectedOption3">
            <option disabled value="">Select Option 3</option>
            <optgroup
              v-for="(roomGroup, locationName) in groupedRooms"
              :key="locationName"
              :label="locationName"
            >
              <option v-for="room in roomGroup" :key="room.id" :value="room.id">
                {{ room.name }} {{ room.room_number }}
              </option>
            </optgroup>
          </select>

          <select v-model="selectedOption4">
            <option disabled value="">Select Option 4</option>
            <option v-for="option in options4" :value="option">
              {{ option }}
            </option>
          </select>
        </div>
        <button
          @click="closeAndEmit"
          class="bg-blue-400 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4"
        >
          Submit
        </button>
      </div>
    </div>
  </div>
</template>