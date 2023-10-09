<script setup>
import { ref, computed, defineEmits, onMounted, watch } from "vue";
import moment from "moment";
import {
  PencilIcon,
  FlagIcon,
  UserIcon,
  CheckIcon,
  ExclamationCircleIcon,
  ExclamationTriangleIcon,
  XMarkIcon,
} from "@heroicons/vue/20/solid";
import axios from "axios";

// --------------------- Defining Props ------------------------
const props = defineProps({
  subjects: { type: Array, required: true },
  praktikums: { type: Array, required: true },
  holidays: { type: Array, required: false },
  feriens: { type: Array, required: false },
  options2: { type: Array },
  rooms: { type: Array, required: true },
  day: { type: String, required: true },
  gridSlotItem: { type: Object, required: true },
  date: { type: Object },
  courseId: { type: Number, required: true },
  calendarWeek: { type: Number, required: true },
  teachers: { type: Array },
  default_room: { type: Number, required: true },
  selectedYear: { type: Number, required: true },
});

// ---------------------- Defining refs ---------------------------
const teachingUnitData = ref(null);
const isLoading = ref(true);
const selectedOption1 = ref("");
const selectedOption2 = ref("");
const selectedOption3 = ref(props.default_room);
const selectedOption4 = ref("");
const showModal = ref(false);
const conflict = ref(false);
const showConflictModal = ref(false);
const conflictDetails = ref(null);

// ------------------ Defining computed properties ----------------------
const selectedTeacherOnLeave = computed(() => {
  if (!selectedOption2.value) return false;
  return isTeacherOnLeave(selectedOption2.value);
});

const selectedRoom = computed(() => {
  return props.rooms.find((room) => room.id === selectedOption3.value);
});

const groupedRooms = computed(() => {
  return props.rooms.reduce((result, room) => {
    const locationName = room.location.name;
    if (result[locationName]) {
      result[locationName].push(room);
    } else {
      result[locationName] = [room];
    }
    return result;
  }, {});
});

const statusIcon = computed(() => {
  if (!teachingUnitData.value) {
    return {
      component: XMarkIcon,
      color: "red",
    };
  }
  const { subject, teacher, room } = teachingUnitData.value;
  if (subject?.id && teacher?.id && room?.id) {
    return {
      component: CheckIcon,
      color: "green",
    };
  }
  if (subject?.id || teacher?.id || room?.id) {
    return {
      component: ExclamationCircleIcon,
      color: "orange",
    };
  }
  return {
    component: XMarkIcon,
    color: "red",
  };
});

const isCurrentTeacherOnLeave = computed(() => {
  if (!teachingUnitData.value?.teacher) {
    return false;
  }
  return isTeacherOnLeave(teachingUnitData.value.teacher);
});

const sortedSubjects = computed(() => {
  return [...props.subjects].sort((a, b) => {
    const nameA = a.name.toUpperCase(); // Ignore case
    const nameB = b.name.toUpperCase(); // Ignore case
    if (nameA < nameB) return -1;
    if (nameA > nameB) return 1;
    return 0; // names are equal
  });
});

const sortedTeachers = computed(() => {
  // Assuming selectedOption1 holds the selected subject object
  const selectedSubjectId = selectedOption1.value?.id; // Access the id of the selected subject
  
  if (!selectedSubjectId) return [];
  
  const selectedSubject = sortedSubjects.value.find(subject => subject.id === selectedSubjectId);
  
  if (!selectedSubject || !selectedSubject.teachers) return [];
  
  return [...selectedSubject.teachers].sort((a, b) => {
    const nameA = a.surname.toUpperCase(); // Ignore case
    const nameB = b.surname.toUpperCase(); // Ignore case
    if (nameA < nameB) return -1;
    if (nameA > nameB) return 1;
    return 0; // names are equal
  });
});
// ----------------- Defining Helper Functions -----------------------
const checkTeachingUnit = async () => {
  // set teachingUnitData to null
  teachingUnitData.value = null;
  conflictDetails.value = null;
  conflict.value = false;
  const { data } = await axios.post("/stundenplan/check-teaching-unit", {
    week: props.calendarWeek,
    year: props.selectedYear,
    course_id: props.courseId,
    start_time: props.gridSlotItem.start_time,
    end_time: props.gridSlotItem.end_time,
    date: props.date.format("YYYY-MM-DD"),
  });
  if (data.dataExists) {
    teachingUnitData.value = data.detail;
    emit("emitSollData", data.soll);
    // Also set selectedOption3 if data exists
    selectedOption3.value = data.detail.room
      ? data.detail.room.id
      : props.default_room;
    if (data.conflict) {
      conflict.value = true;
      conflictDetails.value = data.overlappingSchedules;
    }
  } else {
    selectedOption3.value = props.default_room;
  }
};

const isLeavePeriodOverlapping = (
  { awaystartdate, awayenddate },
  weekStartDate,
  weekEndDate
) => {
  const start = moment(awaystartdate);
  const end = moment(awayenddate);
  return start.isBefore(weekEndDate) && end.isAfter(weekStartDate);
};

const isTeacherOnLeave = (teacher) => {
  if (!teacher || !teacher.teacher_not_available) {
    return false; // If the teacher has no leave dates, return false
  }
  const specificDate = moment(props.date).startOf("day");
  return teacher.teacher_not_available.some((leavePeriod) => {
    const start = moment(leavePeriod.awaystartdate).startOf("day");
    const end = moment(leavePeriod.awayenddate).startOf("day");
    const isOnLeave = specificDate.isBetween(start, end, null, "[]");
    return isOnLeave;
  });
};

const loadSavedData = () => {
  if (teachingUnitData.value) {
    selectedOption1.value = props.subjects.find(
      (option) => option.id === teachingUnitData.value.subject.id
    );
    if (teachingUnitData.value && teachingUnitData.value.teacher) {
      selectedOption2.value = selectedOption1.value.teachers.find(
        (teacher) => teacher.id === teachingUnitData.value.teacher.id
      );
    }
    // check that teachingUnitData.value.room is defined before setting selectedOption3
    if (teachingUnitData.value && teachingUnitData.value.room) {
      selectedOption3.value = teachingUnitData.value.room.id;
    } else {
      // if teachingUnitData.value.room is not defined, set selectedOption3 to default_room
      selectedOption3.value = props.default_room;
    }
  }
};

const holidayName = computed(() => {
  const holiday = props.holidays.find((holiday) => {
    return moment(holiday.date).isSame(props.date, "day");
  });
  return holiday ? holiday.name : null;
});

const ferienName = computed(() => {
  if (!props.feriens || !Array.isArray(props.feriens)) return null;

  const ferien = props.feriens.find((ferien) => {
    const startDate = moment(ferien.start_date).startOf("day");
    const endDate = moment(ferien.end_date).startOf("day");
    const isBetween = moment(props.date).isBetween(
      startDate,
      endDate,
      null,
      "[]"
    );
    return isBetween;
  });
  return ferien ? ferien.name : null;
});

const praktikumName = computed(() => {
  if (!props.praktikums || !Array.isArray(props.praktikums)) return null;

  const checkedDate = moment(props.date).startOf("day");
  const dayOfWeek = checkedDate.format("dddd");

  const foundPraktikum = props.praktikums.find((praktikum) => {
    const startDate = moment(praktikum.start_date).startOf("day");
    const endDate = moment(praktikum.end_date).startOf("day");

    const isBetween = checkedDate.isBetween(startDate, endDate, null, "[]");
    const isDayIncluded = praktikum.repeat_days.includes(dayOfWeek);

    return isBetween && isDayIncluded;
  });

  return foundPraktikum ? foundPraktikum.name : null;
});

async function initTeachingUnit() {
  await checkTeachingUnit();
  isLoading.value = false;
}

// ----------------- Lifecycle Hooks -----------------------
onMounted(initTeachingUnit);

// ------------------ Watchers -------------------------
const watchCourseAndWeek = () => {
  isLoading.value = true;
  checkTeachingUnit().then(() => {
    isLoading.value = false;
  });
};

watch(
  () => [props.courseId, props.calendarWeek, props.selectedYear],
  watchCourseAndWeek,
  { immediate: true }
);

watch(
  () => props.selectedYear,
  () => {
    isLoading.value = true;
    checkTeachingUnit().then(() => {
      isLoading.value = false;
    });
  },
  { immediate: true }
);

// Watches selectedOption1 changes and logs teacher's availability
watch(
  () => selectedOption1,
  () => {
    // Find the selected subject by its id
    let selectedSubject = props.subjects.find(
      (subject) => subject.id === selectedOption1
    );
    // Check for each teacher's availability
    if (selectedSubject && selectedSubject.teachers) {
      for (let teacher of selectedSubject.teachers) {
        teacherAvailabilities[teacher.id] = isTeacherAvailable(teacher);
      }
    } else {
    }
  },
  { immediate: true }
);

watch(
  () => showModal.value,
  async (newVal) => {
    if (newVal) {
      isLoading.value = true;
      await checkTeachingUnit();
      loadSavedData();
      isLoading.value = false;
    }
  }
);

// ---------------- Emit Definitions --------------------
const emit = defineEmits({ selection: null, emitSollData: null });

// ---------------- Event Emitting Functions --------------------
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

const closeAndEmit = async () => {
  emitSelection();
  showModal.value = false;
  await new Promise((resolve) => setTimeout(resolve, 500));
  checkTeachingUnit();
};
</script>

<template>
  <div v-if="isLoading" class="flex justify-center items-center">
    <div class="spinner"></div>
  </div>
  <div
    class="flex items-center justify-center bg-gray-400 text-white p-2"
    v-else-if="holidayName"
  >
    <div class="font-semibold">{{ holidayName }}</div>
  </div>
  <div
    class="flex items-center justify-center bg-green-200 text-gray-600 p-2"
    v-else-if="ferienName"
  >
    <div class="font-semibold">{{ ferienName }}</div>
  </div>
  <div
    class="flex items-center justify-center p-2 bg-red-200 text-gray-600"
    v-else-if="praktikumName"
  >
    <div class="font-semibold">{{ praktikumName }}</div>
  </div>
  <div v-else>
    <div v-if="teachingUnitData" class="bg-gray-100 p-2 rounded relative">
      <component
        :is="statusIcon.component"
        class="h-5 w-5 absolute top-2 right-2"
        :style="{ color: statusIcon.color }"
      />
      <div class="flex items-center" v-if="teachingUnitData.teacher">
        <p
          class="mb-0"
          :class="{ 'text-red-500 underline': isCurrentTeacherOnLeave }"
        >
          {{ teachingUnitData.teacher.title }}
          {{ teachingUnitData.teacher.surname }}
        </p>
        <UserIcon
          class="h-5 w-5 mr-2"
          :style="{ fill: teachingUnitData.teacher.color }"
        />
        <ExclamationCircleIcon
          v-if="isCurrentTeacherOnLeave"
          class="h-5 w-5 text-red-500 ml-2"
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
      <p
        :class="{
          'bg-green-100': selectedOption3 === default_room,
          'bg-blue-100': selectedOption3 !== default_room,
        }"
      >
        {{ teachingUnitData.room.name }},
        {{ teachingUnitData.room.room_number }}
      </p>
    </div>
    <div v-if="!showModal" class="flex justify-between items-center">
      <button
        @click="showModal = true"
        class="flex justify-center items-center bg-gray-500 hover:bg-gray-700 text-white font-bold m-2 py-1 px-2 rounded w-[40px] h-[40px]"
      >
        <PencilIcon class="h-5 w-5" />
      </button>
      <button
        v-if="conflict"
        class="flex justify-center items-center bg-red-500 font-bold hover:bg-red-700 m-2 px-2 py-1 rounded w-[40px] h-[40px] text-white"
        @click="showConflictModal = !showConflictModal"
      >
        <ExclamationTriangleIcon class="h-5 w-5" />
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
            <option value="" disabled>Select Option 1</option>
            <option value="">Select Later</option>
            <option :value="option" v-for="option in sortedSubjects">
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
            <option value="">Select Later</option>
            <option
              v-for="teacher in sortedTeachers"
              :value="teacher"
              :class="[
                isTeacherOnLeave(teacher)
                  ? 'text-orange-500 cursor-not-allowed'
                  : 'text-green-500',
              ]"
              :disabled="isTeacherOnLeave(teacher)"
            >
              {{ teacher.surname }}, {{ teacher.name }}
            </option>
          </select>

          <select
            v-model="selectedOption3"
            :class="{
              'bg-green-300': selectedOption3 === default_room,
              'bg-blue-300': selectedOption3 !== default_room,
            }"
          >
            <option disabled value="">Select Option 3</option>
            <option value="">Select Later</option>
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

  <div
    class="flex items-center justify-center bg-black bg-opacity-50 fixed inset-0 z-50"
    v-if="showConflictModal"
  >
    <div class="relative bg-white p-6 rounded-xl shadow-md">
      <button
        class="absolute right-2 top-2 font-semibold hover:text-gray-800 text-gray-500 text-xl"
        @click.prevent="showConflictModal = false"
      >
        ×
      </button>
      <h3 class="font-semibold text-xl mb-4">Conflict Details</h3>
      <div v-if="conflictDetails">
        <ul>
          <p>confilcDetails is working</p>
          <li v-for="detail in conflictDetails" :key="detail.id">
            {{ detail.schedual_master.date }}
            {{ detail.schedual_master.course.name }}
          </li>
        </ul>
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
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #09f;
  animation: spinner 1s linear infinite;
}

@keyframes spinner {
  to {
    transform: rotate(1turn);
  }
}
</style>
