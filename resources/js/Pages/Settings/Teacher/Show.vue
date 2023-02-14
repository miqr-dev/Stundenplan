<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Link, Head } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import moment from "moment";
import { ref, computed } from "vue";
import "vue3-swatches/dist/style.css";

const props = defineProps({
  teacher: {},
});

const sortedLeaves = computed(() => props.teacher.teacher_not_available.sort((a, b) => (a.id > b.id) ? -1 : 1))
const showLeaves = ref(false);
const showSubjects = ref(false);


</script>

<template>
  <Head title="Teacher Show" />
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
              <h2>
                {{ teacher.name }}&#44; &nbsp;{{ teacher.surname }}
                <span class="text-sm ml-4">{{ teacher.email }}</span>
              </h2>
              <Link :href="route('teacher.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="mb-6">
                <label
                  class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                  for="maxhourweek"
                >
                  Color
                </label>
                <VSwatches
                  v-model="teacher.color"
                  swatches="text-advanced"
                  row-length="12"
                  show-border
                  popover-x="button"
                  :disabled="true"
                ></VSwatches>
              </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="teacher.maxhourweek"
                    label="Max Hour / Week"
                    type="text"
                    readonly="readonly"
                  />
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
                          Leaves
                        </button>
                        <span class="ml-2 text-md text-blue-500 self-center"
                          >({{ teacher.teacher_not_available.length }})</span
                        >
                      </div>

                      <Link
                        :href="
                          route('teacher.not-available.create', {
                            teacher: teacher.id,
                          })
                        "
                        class="text-blue-500 text-p"
                        >Add Leave</Link
                      >
                    </div>
                    <div
                      class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                      v-show="showLeaves"
                    >
                      <div
                        class="inline-block min-w-full shadow-md rounded-lg overflow-auto h-72"
                      >
                        <table
                          class="min-w-full leading-normal table"
                          
                        >
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
                                Status
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="leaves in sortedLeaves"
                              :key="leaves.id"
                            >
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  {{
                                    moment(leaves.awaystartdate).format("LL")
                                  }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  {{ moment(leaves.awaystartdate).week() }}
                                  &nbsp; KW
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  {{ moment(leaves.awayenddate).format("LL") }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  {{ moment(leaves.awayenddate).week() }} &nbsp;
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
                                  <span class="relative">Paid</span>
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
                          >({{ teacher.subjects.length }})</span
                        >
                      </div>

                      <!-- <Link
                        :href="
                          route('teacher.not-available.create', {
                            teacher: teacher.id,
                          })
                        "
                        class="text-blue-500 text-p"
                        >Add Leave</Link
                      > -->
                    </div>
                    <div
                      class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
                      v-show="showSubjects"
                    >
                      <div
                        class="inline-block min-w-full shadow-md rounded-lg overflow-hidden"
                      >
                        <table
                          class="min-w-full leading-normal table"
                          
                        >
                          <thead>
                            <tr>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                name
                              </th>
                              <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                              >
                                color
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="subject in teacher.subjects"
                              :key="subject.id"
                            >
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  {{ subject.name }}
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <VSwatches
                                  v-model= subject.color 
                                  swatches="text-advanced"
                                  row-length="12"
                                  show-border
                                  popover-x="button"
                                  :disabled="true"
                                ></VSwatches>
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
