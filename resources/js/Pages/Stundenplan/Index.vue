<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import moment from "moment";
import TeachingUnit from "@/Components/TeachingUnit.vue";
import Sidebar from "@/Components/Sidebar.vue";

import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  courses: {
    type: Array,
    required: true,
  },
  weekNumbers: {
    type: Array,
    required: true,
  },
  rooms: {
    type: Array,
    required: true,
  },
});

const isOpen = ref(false); // sidebar is closed by default

const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};

const handleSelection = (data) => {
  const { date, gridSlotItem, selectedOptions } = data;

  const form = useForm({
    week: "",
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
  form.date = date.format("YYYY-MM-DD"); // Add this line
  form.course_id = selectedCourse.value.id;
  form.grid_slot_id = gridSlotItem.id;
  form.start_time = gridSlotItem.start_time;
  form.end_time = gridSlotItem.end_time;
  form.subject_id = selectedOptions[0];
  form.teacher_id = selectedOptions[1];
  form.room_id = selectedOptions[2];
  form.post("/stundenplan/teachingunit");
  
};

const selectedCourse = ref(null);
const selectedWeek = ref(null);

const weekDates = computed(() => {
  if (!selectedWeek.value) return [];

  const currentYear = moment().year();
  const startDate = moment()
    .year(currentYear)
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
    !selectedCourse.value.template ||
    !selectedCourse.value.template.subjects
  ) {
    return [];
  }

  // Extract all the teachers from the subjects of the selected course.
  const teachers = new Set();
  selectedCourse.value.template.subjects.forEach((subject) => {
    if (subject.teachers && subject.teachers.length > 0) {
      subject.teachers.forEach((teacher) => {
        teachers.add(teacher);
      });
    }
  });

  // Return the unique list of teachers as an array.
  return Array.from(teachers);
});

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
      <h2
        class="font-semibold text-xl text-gray-800 leading-tight"
        v-if="selectedCourse"
      >
        <span class="text-blue-400">{{ selectedCourse.name }}</span> In
        <span class="text-red-400">{{
          selectedCourse.room.location.city.name
        }}</span>
        At
        <span class="text-green-400">{{
          selectedCourse.room.location.name
        }}</span>
        In
        <span class="text-green-400">{{
          selectedCourse.room.name
        }}</span> ,
        <span class="text-green-400">{{
          selectedCourse.room.room_number
        }}</span>
      </h2>
      <div class="h-full grid place-items-center bg-gray-800 text-white"></div>
    </template>
    <div class="py-4">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div>
              <div class="flex justify-between">
                <div class="flex space-x-4">
                  <div>
                    <label for="course-select">Select a Course:</label>
                    <select
                      id="course-select"
                      v-model="selectedCourse"
                      class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
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
                  <div>
                    <label for="calenderweek-select"
                      >Select the Calendar Week</label
                    >
                    <select
                      id="calenderweek-select"
                      v-model="selectedWeek"
                      class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                      <option disabled value="">Please select a week</option>
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
                        {{ item.WeekNumber }}
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
                <div>
                  <h3>Grid {{ selectedCourse.grid.name }}</h3>
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
                              moment(gridSlotItem.end_time, "HH:mm:ss").format(
                                "HH:mm"
                              )
                            }}
                          </td>
                          <!-- Generate a button for each day of the week -->
                          <template v-for="dayWithDate in daysWithDates">
                            <!-- Conditionally render the button if a course and week have been selected -->
                            <td
                              class="border px-1 py-1"
                              v-if="selectedCourse && selectedWeek"
                            >
                              <TeachingUnit
                                :subjects="selectedCourse.template.subjects"
                                :default_room= "selectedCourse.room_id"
                                :rooms="rooms"
                                :day="dayWithDate.day"
                                :date="dayWithDate.date"
                                :gridSlotItem="gridSlotItem"
                                :courseId="selectedCourse.id"
                                :calendarWeek="selectedWeek"
                                :teachers="selectedCourseTeachers"
                                @selection="
                                  (...args) => handleSelection(...args)
                                "
                              />
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Sidebar
      :isOpen="isOpen"
      :toggleSidebar="toggleSidebar"
      :teachers="selectedCourseTeachers"
    />
  </BreezeAuthenticatedLayout>
</template>
