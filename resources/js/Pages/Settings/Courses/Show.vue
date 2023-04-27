<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, Head } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { ref } from "vue";
import moment from "moment";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import { CheckBadgeIcon, XCircleIcon } from "@heroicons/vue/20/solid";
import { VTooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps({
  course: {},
});

const showSubjects = ref(false);
</script>

<template>
  <Head title="Course Show" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <div class="flex items-stretch">
                <div>
                  <h2>
                    <Link
                      :href="`/course/${course.id}/edit`"
                      class="hover:underline"
                    >
                      {{ course.name }}
                    </Link>
                  </h2>
                </div>
                <div class="self-center">
                  <span
                    class="inline-block animate-pulse rounded-full text-white text-sm ml-3"
                    :class="course.active ? 'bg-green-600' : 'bg-red-400'"
                  >
                    <CheckBadgeIcon
                      v-if="course.active"
                      v-tooltip="'Active'"
                      class="h-6 w-6"
                    />
                    <XCircleIcon
                      v-tooltip="'Inactive'"
                      v-if="!course.active"
                      class="h-6 w-6"
                    />
                  </span>
                </div>
              </div>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="flex space-x-3 mb-6">
                <div class="w-1/3">
                  <label
                    class="block mb-1 text-xs font-bold text-green-600 uppercase"
                    for="label"
                  >
                    Start on
                  </label>
                  <div class="flex items-stretch">
                    <div class="whitespace-no-wrap text-p-gray">
                      {{ moment(course.start_date).format("LL") }}
                    </div>
                    <div class="text-p-gray text-xs self-start ml-1">
                      ( {{ moment(course.start_date).week() }}. KW )
                    </div>
                  </div>
                </div>
                <div class="w-1/3">
                  <label
                    class="block mb-2 text-xs font-bold text-orange-600 uppercase"
                    for="label"
                  >
                    Finish on
                  </label>
                  <div class="flex items-stretch">
                    <div class="whitespace-no-wrap text-p-gray">
                      {{ moment(course.end_date).format("LL") }}
                    </div>
                    <div class="text-p-gray text-xs self-start ml-1">
                      ( {{ moment(course.end_date).week() }}. KW )
                    </div>
                  </div>
                </div>
                <div class="w-1/3">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="label"
                  >
                    Color
                  </label>
                  <VSwatches
                    v-model="course.color"
                    swatches="text-advanced"
                    row-length="12"
                    show-border
                    popover-x="button"
                    :disabled="true"
                  ></VSwatches>
                </div>
              </div>
              <div class="flex space-x-3 mb-8">
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.name"
                    label="Name"
                    type="text"
                    :readonly="true"
                  />
                </div>
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.lbrn"
                    label="LBRN"
                    type="text"
                    :readonly="true"
                  />
                </div>
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.type"
                    label="Type"
                    type="text"
                    :readonly="true"
                  />
                </div>
              </div>
              <div class="flex space-x-3 mt-8">
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.room.location.city.name"
                    label="City"
                    type="text"
                    :readonly="true"
                  />
                </div>
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.room.location.name"
                    label="Address"
                    type="text"
                    :readonly="true"
                  />
                </div>
                <div class="w-1/3">
                  <SimpleInput
                    v-model="course.room.name"
                    label="Type"
                    type="text"
                    :readonly="true"
                  />
                </div>
              </div>
              <div class="mb-2">
                <div class="container mx-auto">
                  <div class="py-2">
                    <div class="flex justify-between">
                      <div class="flex self-center">
                        <button
                          class="text-2xl font-semibold leading-tight hover:underline"
                          @click="showSubjects = !showSubjects"
                        >
                          Subjects
                        </button>
                        <span class="ml-2 text-md text-blue-500 self-center"
                          >({{ course.template.subjects.length }})</span
                        >
                      </div>
                      <!-- <Link
                        :href="
                          route('teacher.not-available.create', {
                            teacher: teacher.id,
                          })
                        "
                        class="text-blue-500 text-p"
                        >Add Leave</Link> -->
                    </div>
                    <p>
                      This course is using
                      <span class="text-blue-500"
                        ><Link :href="`/template/${course.template.id}`">{{
                          course.template.name
                        }}</Link></span
                      >
                      Template
                    </p>
                    <div
                      class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                      v-show="showSubjects"
                    >
                      <div class="inline-block min-w-full shadow-md rounded-lg">
                        <table class="min-w-full leading-normal table">
                          <thead>
                            <tr>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                Name
                              </th>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                Teachers
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="subject in course.template.subjects"
                              :key="subject.id"
                            >
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p
                                  class="text-gray-900 whitespace-no-wrap hover:underline underline-offset-4"
                                >
                                  <Link :href="`/subject/${subject.id}`">{{
                                    subject.name
                                  }}</Link>
                                </p>
                                <!-- <p class="text-gray-600 whitespace-no-wrap">
                                  {{ moment(subject.awaystartdate).week() }}
                                  &nbsp; KW
                                </p> -->
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <ul
                                  v-for="teacher in subject.teachers"
                                  :key="teacher.id"
                                >
                                  <li>
                                    <Link
                                      class="text-gray-700 whitespace-no-wrap hover:underline underline-offset-4"
                                      :href="`/teacher/${teacher.id}`"
                                      >{{ teacher.name }},
                                      {{ teacher.surname }}</Link
                                    >
                                    <ul
                                      v-for="city in teacher.cities"
                                      :key="city.id"
                                      class="text-sm text-gray-400"
                                    >
                                      <li>{{ city.name }}</li>
                                    </ul>
                                  </li>
                                </ul>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right"
                              >
                                <button
                                  type="button"
                                  class="inline-block text-gray-500 hover:text-gray-700"
                                >
                                  <svg
                                    class="inline-block h-6 w-6 fill-current"
                                    viewBox="0 0 24 24"
                                  >
                                    <path
                                      d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                    />
                                  </svg>
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
