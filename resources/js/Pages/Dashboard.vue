<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/vue3'
</script>



<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">select list</h2>
            <div class="h-full grid place-items-center bg-gray-800 text-white">
            </div>
        </template>


              <div class="mb-2">
                <div class="container mx-auto">
                <ul>
                  <li class="py-2">
                    <div class="flex justify-between">
                      <div class="flex self-center">
                        <button
                          class="text-2xl font-semibold leading-tight hover:underline"
                          
                        >
                          Leaves
                        </button>
                        <span class="ml-2 text-md text-blue-500 self-center"
                          >(4)</span
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
                            >
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  bla bla
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  bla bla
                                  &nbsp; KW
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  bla bla
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  bla bla
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
                  </li>
                  <li class="py-2">
                    <div class="flex justify-between">
                      <div class="flex self-center">
                        <button
                          class="text-2xl font-semibold leading-tight hover:underline"
                          
                        >
                          Leaves
                        </button>
                        <span class="ml-2 text-md text-blue-500 self-center"
                          >(4)</span
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
                            >
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  bla bla
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  bla bla
                                  &nbsp; KW
                                </p>
                              </td>
                              <td
                                class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                              >
                                <p class="text-gray-900 whitespace-no-wrap">
                                  bla bla
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                  bla bla
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
                  </li>
                  </ul>
                </div>
              </div>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>


