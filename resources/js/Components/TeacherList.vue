<script setup>
import { defineEmits, computed, ref } from "vue";
import moment from "moment";
import axios from "axios";

const props = defineProps({
  isTeacherList: {
    type: Boolean,
    required: true,
  },
  toggleTeacherList: {
    type: Function,
    required: true,
  },
  course: {
    type: Object,
  },
  daysWithDates: {
    type: Object,
  },
  selectedWeek: {
    type: Number,
  },
});
const emit = defineEmits();
const busySlots = ref([]);

const daysWithDates = computed(() => {
  const weekdays = [];
  // Start from the current date
  let date = moment();

  // If today is not Monday, find the Monday of this week
  if (date.isoWeekday() !== 1) {
    date.isoWeekday(1); // Set to Monday
  }

  // If there's a selectedWeek and it's not the current week
  if (props.selectedWeek && props.selectedWeek !== date.isoWeek()) {
    const weekDiff = props.selectedWeek - date.isoWeek();
    date = date.add(weekDiff, "weeks"); // Jump to the Monday of the selected week
  }

  for (let i = 0; i < 5; i++) {
    weekdays.push({
      day: date.format("dddd"),
      formattedDate: date.format("dddd DD.MM"),
    });
    date = date.add(1, "days");
  }
  return weekdays;
});
const uniqueTeachers = computed(() => {
  const allTeachers = props.course.subjects.flatMap(
    (subject) => subject.teachers
  );
  const seen = new Set();
  return allTeachers.filter((teacher) => {
    const isDuplicate = seen.has(teacher.id);
    seen.add(teacher.id);
    return !isDuplicate;
  });
});

const closeTeacherList = () => {
  props.toggleTeacherList();
};

const sendData = async (teacherId) => {
  try {
    const response = await axios.post("/teacherList", {
      teacher_id: teacherId,
      selectedWeek: props.selectedWeek,
      course_id: props.course.id,
    });

    busySlots.value = Array.isArray(response.data.busySlots)
      ? response.data.busySlots
      : [];
    console.log(response.data.busySlots);
  } catch (error) {
    console.error("Error sending data", error);
  }
};

const getBusySlot = (dayWithDate, gridSlotItem) => {
  const matchedSlot = busySlots.value.find((slot) => {
    const slotDate = moment(slot.schedual_master.date).format("dddd DD.MM");
    const isDateMatch = slotDate === dayWithDate.formattedDate;
    const isStartTimeMatch = slot.start_time === gridSlotItem.start_time;
    const isEndTimeMatch = slot.end_time === gridSlotItem.end_time;

    return isDateMatch && isStartTimeMatch && isEndTimeMatch;
  });

  console.log(matchedSlot);
  return matchedSlot;
};
</script>
<template>
  <div
    class="fixed inset-0 flex items-center justify-center z-10 overflow-x-auto overflow-y-auto bg-black bg-opacity-50"
    v-if="isTeacherList"
  >
    <div
      class="bg-white overflow-y-auto rounded-lg shadow-xl sm:mx-8 sm:my-8 w-4/5 z-20"
    >
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <div
              class="bg-white rounded-md shadow-md mt-4 p-4 max-w-full mx-auto flex flex-wrap gap-4"
            >
              <div
                v-for="teacher in uniqueTeachers"
                :key="teacher.id"
                class="bg-gray-100 px-4 py-2 rounded-md cursor-pointer"
                @click="sendData(teacher.id)"
              >
                {{ teacher.name.charAt(0) }}.{{ teacher.surname }}
              </div>
            </div>

            <table class="table-auto w-full mt-2 text-p border">
              <thead>
                <tr class="text-left font-bold">
                  <th class="border px-4 py-2"></th>
                  <th
                    class="border py-2 px-4"
                    :key="index"
                    v-for="dayWithDate in daysWithDates"
                  >
                    {{ dayWithDate.formattedDate }}
                    <!-- <span v-if="weekDates.length">{{
                        weekDates[index].format("MMM DD")
                      }}</span> -->
                  </th>
                </tr>
              </thead>
              <tbody>
                <template v-for="gridSlotItem in course.grid.gridslots">
                  <tr class="text-left">
                    <td class="border px-2 py-2">
                      {{
                        moment(gridSlotItem.start_time, "HH:mm:ss").format(
                          "HH.mm"
                        )
                      }}
                      -
                      {{
                        moment(gridSlotItem.end_time, "HH:mm:ss").format(
                          "HH:mm"
                        )
                      }}
                    </td>
                    <!-- Loop through each day of the week -->
                    <td
                      class="border px-1 py-1"
                      v-for="dayWithDate in daysWithDates"
                    >
                      <div v-if="getBusySlot(dayWithDate, gridSlotItem)">
                        {{
                          getBusySlot(dayWithDate, gridSlotItem).subject.name
                        }}
                      </div>
                      <div v-else></div>
                    </td>
                  </tr>
                  <tr v-if="gridSlotItem.is_break">
                    <td class="border px-4 py-1 bg-gray-200">Pause</td>
                    <!-- Generate a column for each day of the week -->
                    <td
                      class="border px-1 py-1"
                      v-for="dayWithDate in daysWithDates"
                      :key="dayWithDate.day"
                    ></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button
          @click="closeTeacherList"
          class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>
