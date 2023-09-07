<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed, watch, defineEmits } from "vue";
import moment from "moment";
import TeachingUnit from "@/Components/TeachingUnit.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  courses: {
    type: Array,
    required: true,
  },
  rooms: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["updateSollDataInSidebar"]);

const selectedCourse = ref(null);
const selectedWeek = ref(null);
const currentYear = moment().year();
const years = Array.from({ length: 7 }, (_, i) => currentYear - 3 + i);
const selectedYear = ref(currentYear);
const sollData = ref(null);

const isCourseInSelectedWeek = computed(() => {
  // If the selectedCourse or selectedWeek is not available, return false
  if (!selectedCourse.value || !selectedWeek.value) {
    console.log("Either course or week is not selected");
    return false;
  }

  // Convert the start and end date of the selected course to moment objects
  const courseStartDate = moment(selectedCourse.value.start_date);
  const courseEndDate = moment(selectedCourse.value.end_date);

  // Get the start and end date of the selected week
  const currentYear = moment().year();
  const weekStartDate = moment()
    .year(selectedYear.value)
    .week(selectedWeek.value)
    .startOf("week");
  const weekEndDate = moment()
    .year(selectedYear.value)
    .week(selectedWeek.value)
    .endOf("week");
  // Check if the course is active during the selected week
  const isActive =
    courseStartDate.isSameOrBefore(weekEndDate, "day") &&
    courseEndDate.isSameOrAfter(weekStartDate, "day") &&
    courseStartDate.year() <= selectedYear.value &&
    courseEndDate.year() >= selectedYear.value;

  console.log("Is course active during the selected week:", isActive);
  return isActive;
});

const weekNumbers = computed(() => {
  const totalWeeks = moment().year(selectedYear.value).isoWeeksInYear();
  const weekNumbers = [];
  for (let i = 1; i <= totalWeeks; i++) {
    weekNumbers.push({ id: i, WeekNumber: i });
  }
  return weekNumbers;
});

watch(selectedYear, (newYear, oldYear) => {});

watch(selectedWeek, (newWeek, oldWeek) => {});
watch(selectedCourse, (newVal, oldVal) => {
  console.log("selectedCourse changed", newVal, oldVal);
});

// Sidebar
const isOpen = ref(false); // sidebar is closed by default
// End Sidebar
const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};

const handleSelection = (data) => {
  const { date, gridSlotItem, selectedOptions } = data;

  const form = useForm({
    week: "",
    year: "",
    template_id: "",
    course_id: "",
    grid_slot_id: "",
    start_time: "",
    end_time: "",
    subject_id: "",
    teacher_id: "",
    room_id: "",
    date: "",
  });

  form.week = date.week();
  form.year = selectedYear.value;
  form.date = date.format("YYYY-MM-DD"); // Add this line
  form.course_id = selectedCourse.value.id;
  form.grid_slot_id = gridSlotItem.id;
  form.start_time = gridSlotItem.start_time;
  form.end_time = gridSlotItem.end_time;
  form.subject_id = selectedOptions[0];
  form.teacher_id = selectedOptions[1];
  form.room_id = selectedOptions[2];
  form.template_id = selectedCourse.value.template_id;
  form.post("/stundenplan/teachingunit");
};

const handleSollData = (newSollData) => {
  console.log("Index: Got new sollData:", newSollData);
  sollData.value = newSollData;
};

const weekDates = computed(() => {
  if (!selectedWeek.value) return [];
  const startDate = moment()
    .year(selectedYear.value)
    .week(selectedWeek.value)
    .startOf("week");
  const dates = [];
  for (let i = 0; i < 5; i++) {
    dates.push(startDate.clone().add(i, "days"));
  }
  return dates;
});

const daysWithDates = computed(() => {
  return ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"].map(
    (day, index) => ({
      day,
      date: weekDates.value[index],
    })
  );
});

const selectedCourseTeachers = computed(() => {
  // If no course is selected or the selected course doesn't have subjects,
  // return an empty array.
  if (
    !selectedCourse.value ||
    !selectedCourse.value ||
    !selectedCourse.value.subjects
  ) {
    return [];
  }

  // Extract all the teachers from the subjects of the selected course.
  const teachers = new Set();
  selectedCourse.value.subjects.forEach((subject) => {
    if (subject.teachers && subject.teachers.length > 0) {
      subject.teachers.forEach((teacher) => {
        teachers.add(teacher);
      });
    }
  });

  // Return the unique list of teachers as an array.
  return Array.from(teachers);
});

const getWeekLabel = (weekNumber, year) => {
  const startDate = moment().year(year).isoWeek(weekNumber).startOf("isoWeek");
  const endDate = moment()
    .year(year)
    .isoWeek(weekNumber)
    .endOf("isoWeek")
    .subtract(2, "days");

  const startDay = startDate.format("DD");
  const endDay = endDate.format("DD");
  const startMonth = startDate.format("MMM");
  const endMonth = endDate.format("MMM");

  let label = `Week ${weekNumber} (${startDay} ${startMonth} - ${endDay} ${endMonth})`;

  return label;
};

// color the Calendar week select box
const getWeekStatus = (weekNumber) => {
  const currentWeekNumber = moment().week();
  if (weekNumber < currentWeekNumber) {
    return "past";
  } else if (weekNumber > currentWeekNumber) {
    return "future";
  } else {
    return "current";
  }
};
// Add any other reactive variables, computed properties or methods here.
</script>

<template>
  <Head :title="'Stundenplan'" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <div class="flex flex-col items-center justify-center">
        <div
          class="rounded-full py-2 px-2 leading-tight relative z-10 bg-gray-500 text-white w-full flex items-center justify-center font-semibold text-lg"
          v-if="selectedCourse"
        >
          {{ selectedCourse.name }}
        </div>
        <h2
          class="font-semibold text-xl bg-gray-100 rounded-full p-2 border-4 border-white text-gray-400 leading-tight relative z-10 flex items-center justify-center"
          v-if="selectedCourse"
          style="
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
          "
        >
          <span class="text-blue-600">{{
            selectedCourse.room.location.city.name
          }}</span
          >&nbsp;&nbsp;&nbsp;
          <span class="text-gray-500">{{
            selectedCourse.room.location.name
          }}</span
          >&nbsp;
          <span class="text-gray-500">{{ selectedCourse.room.name }}</span
          >&nbsp;
          <span class="text-gray-500">{{
            selectedCourse.room.room_number
          }}</span>
        </h2>
      </div>
      <div class="h-full grid place-items-center bg-gray-800 text-white"></div>
    </template>
    <div class="py-4">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div>
              <div class="flex justify-between">
                <div class="flex space-x-4">
                  <!-- Course Select -->
                  <div class="w-4/5">
                    <label
                      for="course-select"
                      class="block text-sm font-medium text-gray-700"
                      >Course</label
                    >
                    <select
                      id="course-select"
                      v-model="selectedCourse"
                      class="form-select mt-1 block w-96 py-2 px-3 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-indigo-500"
                    >
                      <option disabled value="">Please select a course</option>
                      <option
                        v-for="courseItem in courses"
                        :key="courseItem.id"
                        :value="courseItem"
                      >
                        {{ courseItem.name }}
                      </option>
                    </select>
                  </div>

                  <!-- Calendar Week Select -->
                  <div class="w-4/5">
                    <label
                      for="calenderweek-select"
                      class="block text-sm font-medium text-gray-700"
                      >KW</label
                    >
                    <select
                      id="calenderweek-select"
                      v-model="selectedWeek"
                      class="form-select mt-1 block w-96 py-2 px-3 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-indigo-500"
                    >
                      <option disabled value="" selected>Select a week</option>
                      <option
                        v-for="item in weekNumbers"
                        :key="item.id"
                        :value="item.WeekNumber"
                        :class="{
                          'bg-red-50 text-red-600':
                            getWeekStatus(item.WeekNumber) === 'past',
                          'bg-blue-50 text-blue-600':
                            getWeekStatus(item.WeekNumber) === 'future',
                          'bg-green-50 text-green-600':
                            getWeekStatus(item.WeekNumber) === 'current',
                        }"
                      >
                        {{ getWeekLabel(item.WeekNumber, selectedYear) }}
                      </option>
                    </select>
                  </div>
                  <!-- Year Select -->
                  <div class="w-3/5">
                    <label
                      for="year-select"
                      class="block text-sm font-medium text-gray-700"
                      >Year</label
                    >
                    <select
                      id="year-select"
                      v-model="selectedYear"
                      class="form-select mt-1 block w-24 py-2 px-3 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-opacity-50 focus:ring-indigo-500"
                    >
                      <option
                        v-for="year in years"
                        :key="year"
                        :value="year"
                        :selected="year === currentYear"
                      >
                        {{ year }}
                      </option>
                    </select>
                  </div>
                </div>

                <button
                  @click="toggleSidebar"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded h-full self-center"
                >
                  Details
                </button>
              </div>
              <div v-if="selectedCourse">
                <div class="mt-4">
                  <!-- <h3>Grid {{ selectedCourse.grid.name }}</h3> -->
                  <template v-if="selectedCourse && selectedWeek">
                    <table class="table-auto w-full mt-1 text-p border">
                      <thead>
                        <tr class="text-left font-bold">
                          <th class="border px-4 py-2"></th>
                          <th
                            class="border px-4 py-2"
                            v-for="(day, index) in [
                              'Monday',
                              'Tuesday',
                              'Wednesday',
                              'Thursday',
                              'Friday',
                            ]"
                            :key="index"
                          >
                            {{ day }}
                            <span v-if="weekDates.length">{{
                              weekDates[index].format("MMM DD")
                            }}</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <template
                          v-for="gridSlotItem in selectedCourse.grid.gridslots"
                          :key="gridSlotItem.id"
                        >
                          <tr class="text-left">
                            <td class="border px-2 py-2">
                              {{
                                moment(
                                  gridSlotItem.start_time,
                                  "HH:mm:ss"
                                ).format("HH.mm")
                              }}
                              -
                              {{
                                moment(
                                  gridSlotItem.end_time,
                                  "HH:mm:ss"
                                ).format("HH:mm")
                              }}
                            </td>
                            <!-- Loop through each day of the week -->
                            <template v-for="dayWithDate in daysWithDates">
                              <td class="border px-1 py-1">
                                <template v-if="selectedCourse && selectedWeek">
                                  <!-- Check if the course is active during the selected week -->
                                  <div v-if="isCourseInSelectedWeek">
                                    <TeachingUnit
                                      :subjects="selectedCourse.subjects"
                                      :default_room="selectedCourse.room_id"
                                      :rooms="rooms"
                                      :day="dayWithDate.day"
                                      :date="dayWithDate.date"
                                      :gridSlotItem="gridSlotItem"
                                      :courseId="selectedCourse.id"
                                      :calendarWeek="selectedWeek"
                                      :selectedYear="selectedYear"
                                      :teachers="selectedCourseTeachers"
                                      @emitSollData="handleSollData"
                                      @selection="
                                        (...args) => handleSelection(...args)
                                      "
                                    />
                                  </div>
                                  <!-- If the course is not active, display a message -->
                                  <div v-else>Course is not available here</div>
                                </template>
                              </td>
                            </template>
                          </tr>
                          <tr v-if="gridSlotItem.is_break">
                            <td class="border px-4 py-1 bg-gray-200">Pause</td>
                            <!-- Generate a button for each day of the week -->
                          </tr>
                        </template>
                      </tbody>
                    </table>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Sidebar
      :sollData="sollData"
      :isOpen="isOpen"
      :toggleSidebar="toggleSidebar"
      :teachers="selectedCourseTeachers"
      :course="selectedCourse"
    />
  </BreezeAuthenticatedLayout>
</template>
