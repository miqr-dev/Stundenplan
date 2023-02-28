<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import moment from "moment";
import { PencilIcon, PlusIcon } from "@heroicons/vue/20/solid";
import { VTooltip } from "floating-vue";
import "floating-vue/dist/style.css";

const props = defineProps({
  grid: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Head title="Grid Show" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2 flex">
                <span class="text-blue-500">
                  <Link :href="`/grid/${grid.id}`"> {{ props.grid.name }}</Link>
                </span>
                <div>
                  <Link :href="`/grid/${grid.id}/edit`">
                    <PencilIcon
                      v-tooltip="'Edit the name and the description'"
                      class="ml-3 w-6 h-6 text-red-400"
                    />
                  </Link>
                </div>
                <div>
                  <Link :href="route('gridslot.create', { grid: grid.id })">
                    <PlusIcon
                      v-tooltip="'Add Timeslots'"
                      class="ml-3 w-6 h-6 text-emerald-400"
                    />
                  </Link>
                </div>
              </h2>
              <Link :href="route('grid.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <p class="m-2 text-base">{{ grid.description }}</p>
            <div
              class="bg-gray-100 p-1 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <div class="flex justify-between"> 
                <Link :href="route('gridslot.create', { grid: grid.id })">
                  <PlusIcon
                    v-tooltip="'Add Timeslots'"
                    class="ml-3 w-6 h-6 text-emerald-400"
                  />
                </Link>
                <Link :href="`/grid/${grid.id}/edit`">
                    <PencilIcon
                      v-tooltip="'Edit the time slots'"
                      class="ml-3 w-6 h-6 text-red-400"
                    />
                  </Link>
              </div>
              <table class="table-auto w-full mt-1 text-p border">
                <thead>
                  <tr class="text-left font-bold">
                    <th class="border px-4 py-2"></th>
                    <th class="border px-4 py-2">Monday</th>
                    <th class="border px-4 py-2">Tuesday</th>
                    <th class="border px-4 py-2">Wednesday</th>
                    <th class="border px-4 py-2">Thrusday</th>
                    <th class="border px-4 py-2">Friday</th>
                  </tr>
                </thead>
                <tbody>
                  <tr></tr>
                  <template
                    v-for="gridslot in grid.gridslots"
                    :key="gridslot.id"
                  >
                    <tr class="text-left">
                      <td class="border px-4 py-2">
                        {{
                          moment(gridslot.start_time, "HH:mm:ss").format(
                            "HH.mm"
                          )
                        }}
                        -
                        {{
                          moment(gridslot.end_time, "HH:mm:ss").format("HH:mm")
                        }}
                      </td>
                    </tr>
                    <tr v-if="gridslot.is_break">
                      <td class="border px-4 py-1 bg-gray-200">Pause</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
