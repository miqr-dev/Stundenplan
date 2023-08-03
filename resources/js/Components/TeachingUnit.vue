<script setup>
import { ref, computed, defineEmits, onMounted, watch } from "vue";
import moment from "moment";
import {
  PencilIcon,
  FlagIcon,
  UserIcon,
  CheckIcon,
  ExclamationCircleIcon,
  XMarkIcon,
} from "@heroicons/vue/20/solid";

import axios from "axios";

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
  teachers: {
    type: Array,
  },
});

// fill out the table with each hours info
const teachingUnitData = ref(null);

// On mount, fetch teaching unit data
onMounted(() => {
  checkTeachingUnit();
});

// Function to fetch teaching unit data from backend
const checkTeachingUnit = async () => {
  // set teachingUnitData to null
  teachingUnitData.value = null;
  const { data } = await axios.post("/stundenplan/check-teaching-unit", {
    week: props.calendarWeek,
    course_id: props.courseId,
    start_time: props.gridSlotItem.start_time,
    end_time: props.gridSlotItem.end_time,
    date: props.date.format("YYYY-MM-DD"),
  });

  if (data.dataExists) {
    teachingUnitData.value = data.detail;
  }
  return data;
};

// Watch for changes in courseId or calendarWeek, and re-fetch data if changed
watch(
  () => [props.courseId, props.calendarWeek],
  async () => {
    await checkTeachingUnit();
  },
  { immediate: true }
); // This options object will cause the watcher to trigger immediately, similarly to the onMounted() lifecycle hook

// Watches selectedOption1 changes and logs teacher's availability
watch(
  () => selectedOption1,
  () => {
    console.log("selectedOption1 updated:", selectedOption1);

    // Find the selected subject by its id
    let selectedSubject = props.options1.find(
      (subject) => subject.id === selectedOption1
    );
    // Check for each teacher's availability
    if (selectedSubject && selectedSubject.teachers) {
      for (let teacher of selectedSubject.teachers) {
        console.log("Checking teacher:", teacher);
        teacherAvailabilities[teacher.id] = isTeacherAvailable(teacher);
      }
    } else {
      console.log("selectedSubject or selectedSubject.teachers is undefined");
    }
  },
  { immediate: true }
);

// Define a custom emit event 'selection'
const emit = defineEmits({ selection: null });
// Defining refs to keep track of selected options and modal visibility
const selectedOption1 = ref("");
const selectedOption2 = ref("");
const selectedOption3 = ref("");
const selectedOption4 = ref("");
const showModal = ref(false);

// Function to load saved data on page not on Table cells if available
const loadSavedData = () => {
  if (teachingUnitData.value) {
    selectedOption1.value = props.options1.find(
      (option) => option.id === teachingUnitData.value.subject.id
    );
    console.log("selected Option1".selectedOption1);
    if (teachingUnitData.value && teachingUnitData.value.teacher) {
      selectedOption2.value = selectedOption1.value.teachers.find(
        (teacher) => teacher.id === teachingUnitData.value.teacher.id
      );
    }
    selectedOption3.value = teachingUnitData.value.room.id;
    // Fill selectedOption4.value similarly, if needed
  }
};

const teachersOnLeave = computed(() => {
  return props.teachers.filter(isTeacherOnLeave);
});

const isTeacherOnLeave = (teacher) => {
  if (!teacher || !teacher.teacher_not_available) {
    // If the teacher has no leave dates, return false
    return false;
  }
  // Get the start and end of the current week
  const weekStartDate = moment().startOf("week").week(props.calendarWeek);
  const weekEndDate = moment().endOf("week").week(props.calendarWeek);
  // Go through all leave periods and check if one overlaps with the selected week
  for (let leavePeriod of teacher.teacher_not_available) {
    const awaystartdate = moment(leavePeriod.awaystartdate);
    const awayenddate = moment(leavePeriod.awayenddate);
    if (
      awaystartdate.isBefore(weekEndDate) &&
      awayenddate.isAfter(weekStartDate)
    ) {
      // If one leave period overlaps with the selected week, the teacher is on leave
      return true;
    }
  }

  // If none of the leave periods overlap with the selected week, the teacher is not on leave
  return false;
};

const selectedTeacherOnLeave = computed(() => {
  if (!selectedOption2.value) return false;
  return isTeacherOnLeave(selectedOption2.value);
});

watch(
  () => selectedOption2.value,
  (newVal, oldVal) => {
    if (newVal && isTeacherOnLeave(newVal)) {
      console.log("Selected teacher is on leave:", newVal);
    } else {
      console.log("Selected teacher is not on leave or no teacher is selected");
    }
  }
);

// computed property to find selected room
const selectedRoom = computed(() => {
  return props.options3.find((room) => room.id === selectedOption3.value);
});

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
// Function to emit selection and close the modal
const closeAndEmit = async () => {
  emitSelection();
  showModal.value = false;
  await new Promise((resolve) => setTimeout(resolve, 500));
  checkTeachingUnit();
};

// computed property to group rooms by location
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

// watch for changes in showModal value and load saved data if true
watch(
  () => showModal.value,
  async (newVal) => {
    if (newVal) {
      // ensure data is loaded before attempting to load the saved data
      await checkTeachingUnit();
      loadSavedData();
    }
  }
);

// computed property to determine status icon
const statusIcon = computed(() => {
  if (
    teachingUnitData.value &&
    teachingUnitData.value.subject &&
    teachingUnitData.value.subject.id &&
    teachingUnitData.value.teacher &&
    teachingUnitData.value.teacher.id &&
    teachingUnitData.value.room &&
    teachingUnitData.value.room.id
  ) {
    return {
      component: CheckIcon,
      color: "green",
    };
  } else if (
    teachingUnitData.value ||
    teachingUnitData.value.subject ||
    teachingUnitData.value.subject.id ||
    teachingUnitData.value.teacher ||
    teachingUnitData.value.teacher.id ||
    teachingUnitData.value.room ||
    teachingUnitData.value.room.id
  ) {
    return {
      component: ExclamationCircleIcon,
      color: "orange",
    };
  } else {
    return {
      component: XMarkIcon,
      color: "red",
    };
  }
});
</script>

<template>
  <div>
    <div v-if="teachingUnitData" class="bg-gray-100 p-2 rounded relative">
      <component
        :is="statusIcon.component"
        class="h-5 w-5 absolute top-2 right-2"
        :style="{ color: statusIcon.color }"
      />
      <div class="flex items-center" v-if="teachingUnitData.teacher">
        <p class="mb-0">
          {{ teachingUnitData.teacher.surname }},
          {{ teachingUnitData.teacher.name }}
        </p>
        <UserIcon
          class="h-5 w-5 mr-2"
          :style="{ fill: teachingUnitData.teacher.color }"
        />
      </div>
      <div class="flex items-center" v-if="!teachingUnitData.teacher">
        <p class="mb-0 text-red-500">Teacher is not selected!</p>
        <UserIcon class="h-5 w-5 mr-2 text-red-800" />
      </div>
      <div class="flex items-center">
        <p class="mb-0">{{ teachingUnitData.subject.name }}</p>
        <FlagIcon
          class="h-5 w-5 mr-2"
          :style="{ fill: teachingUnitData.subject.color }"
        />
      </div>
      <p>{{ teachingUnitData.room.name }}</p>
    </div>
    <div v-if="!showModal">
      <button
        @click="showModal = true"
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold m-2 py-2 px-4 rounded inline-flex items-center"
      >
        <PencilIcon class="h-5 w-5" />
      </button>
    </div>
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    >
      <div class="bg-white p-6 rounded-xl shadow-md relative">
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
          <select v-model="selectedOption1">
            <option disabled value="">Select Option 1</option>
            <option v-for="option in options1" :value="option">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                class="h-4 w-4 inline mr-1"
                :style="{ fill: option.color }"
              >
                <path
                  fill-rule="evenodd"
                  d="M2 4a2 2 0 012-2h12a2 2 0 012 2v11a1 1 0 01-1 1v1l-3-1-3 1v-1a1 1 0 01-1-1V4zm2 0v2h10V4H4z"
                  clip-rule="evenodd"
                />
              </svg>
              {{ option.name }}
            </option>
          </select>
          <select v-model="selectedOption2">
            <option disabled value="">Select Option 2</option>
            <option
              v-for="option in selectedOption1.teachers"
              :value="option"
              :class="[
                isTeacherOnLeave(option)
                  ? 'text-orange-500 cursor-not-allowed'
                  : 'text-green-500',
                selectedTeacherOnLeave.value &&
                selectedOption2.value.id === option.id
                  ? 'text-red-500'
                  : '',
              ]"
              :disabled="isTeacherOnLeave(option)"
            >
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

<style scoped>
.unavailable-teacher {
  color: orange;
}
.selected-teacher-on-leave {
  color: red;
}
</style>
