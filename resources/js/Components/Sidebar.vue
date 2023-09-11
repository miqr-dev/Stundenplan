<script setup>
import { computed, ref, watchEffect, defineEmits } from "vue";
import moment from "moment";

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  toggleSidebar: {
    type: Function,
    required: true,
  },
  teachers: {
    type: Array,
  },
  course: {
    type: Object,
  },
  sollData: {
    type: Object,
    required: false, // or true, depends on your requirements
  },
});

const emit = defineEmits();
let subjects = ref([]);

watchEffect(() => {
  if (props.course) {
    subjects.value = props.course.subjects;
  } else {
    subjects.value = []; // Reset to empty array if no course is selected
  }
});

watchEffect(() => {
  if (props.updateSollDataInSidebar) {
    props.updateSollDataInSidebar(sollData);
  }
});

const updateSollData = (sollData) => {
  sollData.forEach((soll) => {
    const subjectIndex = subjects.value.findIndex(
      (subject) => subject.id === soll.subject_id
    );
    if (subjectIndex !== -1) {
      subjects.value[subjectIndex].pivot.ist = soll.ist;
    }
  });

  subjects.value = [...subjects.value];
};
watchEffect(() => {
  if (props.sollData) {
    console.log("We Are in WatchEffect to run the function ");
    updateSollData(props.sollData);
  }
});

const closeSidebar = () => {
  props.toggleSidebar();
};

const currentMonth = moment().month();
const currentYear = moment().year();

const isDateInCurrentOrNextMonth = (date) => {
  const dateMonth = moment(date).month();
  const dateYear = moment(date).year();
  return (
    (dateMonth === currentMonth && dateYear === currentYear) ||
    (dateMonth === (currentMonth + 1) % 12 &&
      dateYear === (currentMonth === 11 ? currentYear + 1 : currentYear))
  );
};

const filterLeavesForCurrentAndNextMonth = (leaves) =>
  leaves.filter(
    ({ awaystartdate, awayenddate }) =>
      isDateInCurrentOrNextMonth(awaystartdate) ||
      isDateInCurrentOrNextMonth(awayenddate)
  );

const getClassForLeave = (leave) => {
  const today = moment().startOf("day");
  const leaveStart = moment(leave.awaystartdate).startOf("day");
  const leaveEnd = moment(leave.awayenddate).startOf("day");

  // If the leave is already taken (in the past) and both start and end date are in the same month, then Black
  if (
    leaveStart.month() === leaveEnd.month() &&
    leaveStart.year() === leaveEnd.year() &&
    leaveEnd.isBefore(today)
  ) {
    return "text-black";
  }

  // If the current day is inside the leave range (no matter when started), then Orange
  if (today.isBetween(leaveStart, leaveEnd, undefined, "[]")) {
    return "text-orange-500";
  }

  // If the leave starts after today and within the current month or next month, then Blue
  if (isDateInCurrentOrNextMonth(leaveStart)) {
    return "text-blue-500";
  }

  return "text-black";
};

const teachersWithLeavesThisMonth = computed(() => {
  const uniqueTeachers = {};

  return props.teachers.filter((teacher) => {
    if (uniqueTeachers[teacher.id]) return false;

    const hasLeaveThisOrNextMonth = filterLeavesForCurrentAndNextMonth(
      teacher.teacher_not_available
    ).length;

    if (hasLeaveThisOrNextMonth) {
      uniqueTeachers[teacher.id] = true;
    }

    return hasLeaveThisOrNextMonth;
  });
});

const leavesForCurrentAndNextMonth = (teacher) => {
  const leaves = filterLeavesForCurrentAndNextMonth(
    teacher.teacher_not_available
  );

  const uniqueLeaves = Array.from(new Set(leaves.map((l) => l.id))).map((id) =>
    leaves.find((l) => l.id === id)
  );

  uniqueLeaves.sort((a, b) => {
    const dateA = moment(a.awaystartdate);
    const dateB = moment(b.awaystartdate);
    return dateA.isAfter(dateB) ? 1 : -1;
  });

  return uniqueLeaves;
};

const remainingWeeks = computed(() => {
  const endDate = moment(props.course.end_date);
  const today = moment().startOf("day");
  const weeksLeft = endDate.diff(today, "weeks");

  return weeksLeft;
});

const subjectsWithRequiredHours = computed(() => {
  return subjects.value.map((subject) => {
    // Log initial subject data
    console.log(`Calculating for subject: ${subject.name}`);
    console.log(`Initial Soll: ${subject.pivot.soll}`);
    console.log(`Initial Ist: ${subject.pivot.ist}`);

    // Calculate and log remaining Soll
    const remainingSoll = subject.pivot.soll - subject.pivot.ist;
    console.log(`Remaining Soll: ${remainingSoll}`);

    // Log remaining weeks
    console.log(`Remaining Weeks: ${remainingWeeks.value}`);

    // Calculate and log requiredHoursPerWeek
    const requiredHoursPerWeek =
      remainingWeeks.value > 0 ? remainingSoll / remainingWeeks.value : 0;
    console.log(`Required Hours Per Week: ${requiredHoursPerWeek}`);

    // Return new subject object with added requiredHoursPerWeek
    return {
      ...subject,
      requiredHoursPerWeek: requiredHoursPerWeek.toFixed(2),
    };
  });
});
</script>

<template>
  <transition name="slide">
    <aside
      v-if="props.isOpen"
      class="fixed top-0 right-0 h-full w-3/4 md:w-1/2 p-6 bg-white shadow-lg overflow-y-auto z-50"
    >
      <div class="flex justify-end">
        <button @click="closeSidebar" class="close-btn">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="red"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
      <h2 class="text-xl font-semibold mb-4">
        Teachers with Leaves this Month
      </h2>

      <div class="mb-4 text-sm">
        <p><span class="text-orange-500 font-bold">■</span> aktuell abwesend</p>
        <p><span class="text-blue-500 font-bold">■</span> demnächst abwesend</p>
      </div>

      <!-- Table Starts Here -->
      <table class="min-w-full bg-white border border-gray-300">
        <thead>
          <tr>
            <th class="py-2 px-4 border">Teacher Name</th>
            <th class="py-2 px-4 border">Leaves</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="teacher in teachersWithLeavesThisMonth">
            <tr
              v-for="(leave, index) in leavesForCurrentAndNextMonth(teacher)"
              :key="leave.id"
            >
              <td class="py-2 px-4 border" v-if="index === 0">
                {{ teacher.name }} - {{ teacher.surname }}
              </td>
              <td class="py-2 px-4 border" v-else></td>
              <td class="py-2 px-4 border">
                <span :class="getClassForLeave(leave)">
                  {{ moment(leave.awaystartdate).format("MMM DD") }} -
                  {{ moment(leave.awayenddate).format("MMM DD") }}
                </span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
      <!-- Table Ends Here -->

      <h2 class="mt-8 mb-4 font-semibold text-xl">
        Subjects and Soll-Ist Values
      </h2>
<table class="border bg-white border-gray-300 min-w-0 mt-4 w-full">
  <thead>
    <tr>
      <th class="border px-2 py-1">Subject Name</th>
      <th class="border px-2 py-1">Soll</th>
      <th class="border px-2 py-1">Ist</th>
      <th class="border px-2 py-1">Difference</th>
      <th class="border px-2 py-1">Required Hours/Week</th>
    </tr>
  </thead>
  <tbody>
    <tr :key="subject.id" v-for="subject in subjectsWithRequiredHours">
      <td class="border px-2 py-1 text-sm">{{ subject.name }}</td>
      <td class="border px-2 py-1 text-sm">{{ subject.pivot.soll }}</td>
      <td class="border px-2 py-1 text-sm">{{ subject.pivot.ist }}</td>
      <td class="border px-2 py-1 text-sm">{{ subject.pivot.soll - subject.pivot.ist }}</td>
      <td class="border px-2 py-1 text-sm">{{ subject.requiredHoursPerWeek }}</td>
    </tr>
  </tbody>
</table>
    </aside>
  </transition>
</template>

<!-- Your styles remain the same -->
<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter,
.slide-leave-to {
  transform: translateX(100%);
}

.close-btn {
  cursor: pointer;
  background: none;
  border: none;
}
</style>
