<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, Head } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { ref, computed } from "vue";
import moment from "moment";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import { CheckBadgeIcon, XCircleIcon } from "@heroicons/vue/20/solid";
import "floating-vue/dist/style.css";
import { VTooltip } from "floating-vue";

const props = defineProps({
  course: {},
});

const showLeaves = ref(false);

const subjectDifference = computed(() => {
  const courseSubjectCount = props.course.subjects.length;
  const templateSubjectCount = props.course.template.subjects.length;
  const templateName = props.course.template.name;

  const courseSubjectIds = props.course.subjects.map((subject) => subject.id);
  const failedSubjects = props.course.template.subjects.filter(
    (subject) => !courseSubjectIds.includes(subject.id)
  );

  const difference = failedSubjects.length;

  if (difference !== 0) {
    return {
      message: `This course failed ${difference} subject(s) from the template ${templateName} total ${templateSubjectCount}`,
      failedSubjects: `<ol>${failedSubjects
        .map((subject) => `<li>${subject.name}</li>`)
        .join("")}</ol>`,
    };
  }
  return { message: "", failedSubjects: "" };
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
              <div class="w-2/3 flex items-center">
                <h2>
                  <Link
                    :href="`/course/${course.id}/edit`"
                    class="hover:underline"
                  >
                    {{ course.name }}
                  </Link>
                </h2>
                <span
                  class="inline-block animate-pulse rounded-full text-white text-sm ml-2"
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
              <!-- Add Address Information Here -->
              <div class="w-1/3">
                <!-- Increase the value here to move the address more to the right -->
                <div class="p-3 bg-gray-100">
                  <div class="flex items-center mb-1">
                    <span class="text-sm text-secondary-gray-gray">{{
                      course.room.location.city.name
                    }}</span>
                    <span class="text-sm text-secondary-gray-gray mx-1">,</span>
                    <span class="text-sm text-secondary-gray-gray">{{
                      course.room.location.name
                    }}</span>
                  </div>
                  <div class="flex items-center">
                    <span class="text-sm text-secondary-gray-gray"
                      >{{ course.room.name }},
                      {{ course.room.room_number }}</span
                    >
                  </div>
                </div>
              </div>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-6 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="flex space-x-3">
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

              <div class="flex space-x-3">
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
                          >({{ course.subjects.length }})</span
                        >
                      </div>
                      <div
                        v-if="subjectDifference.message"
                        class="self-center text-red-500"
                        v-tooltip="{
                          content: subjectDifference.failedSubjects,
                          html: true,
                        }"
                      >
                        {{ subjectDifference.message }}
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
                                Soll
                              </th>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                Ist
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
                              v-for="subject in course.subjects"
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
                                {{ subject.pivot.soll }}
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                {{ subject.pivot.ist }}
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
              <div class="mb-2">
                <div class="container mx-auto">
                  <div class="py-2">
                    <div class="flex justify-between">
                      <div class="flex self-center">
                        <button
                          class="text-2xl font-semibold leading-tight hover:underline"
                          @click="showLeaves = !showLeaves"
                        >
                          Ferienzeitraum
                        </button>
                        <span class="ml-2 text-md text-blue-500 self-center"
                          >({{ course.feriens.length }})</span
                        >
                      </div>

                      <Link
                        :href="
                          route('ferien.create', {
                            course: course.id,
                          })
                        "
                        class="text-blue-500 text-p"
                        >Neuer Ferienzeitraum</Link
                      >
                    </div>
                    <div
                      class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                      v-show="showLeaves"
                    >
                      <div
                        class="inline-block min-w-full shadow-md rounded-lg overflow-auto h-72"
                      >
                        <table class="min-w-full leading-normal table">
                          <thead>
                            <tr>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                From
                              </th>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                To
                              </th>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                name
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="ferien in course.feriens" :key="ferien.id">
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  {{
                                    moment(ferien.start_date).format("LL")
                                  }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  {{ moment(ferien.start_date).week() }}
                                  &nbsp; KW
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  {{ moment(ferien.end_date).format("LL") }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  {{ moment(ferien.end_date).week() }} &nbsp;
                                  KW
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <span
                                  class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight"
                                >
                                  <span
                                    aria-hidden
                                    class="absolute inset-0 bg-green-200 opacity-50 rounded-full"
                                  ></span>
                                  <span class="relative" v-if="ferien.name"> {{ ferien.name  }}</span>
                                </span>
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
