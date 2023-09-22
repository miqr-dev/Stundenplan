<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import moment from "moment";


const props = defineProps({
  grid: {},
});

const form = useForm({
  grid_id: props.grid.id,
  start_times: ["08:00"],
  end_times: ["08:45"],
  breaks: [{ is_break: false }],
});

const startTimes = ref(form.start_times);
const endTimes = ref(form.end_times);
const breaks = ref(form.breaks);

const addRow = () => {
  startTimes.value.push("");
  endTimes.value.push("");
  breaks.value.push({ is_break: false });
};

const removeRow = (index) => {
  startTimes.value.splice(index, 1);
  endTimes.value.splice(index, 1);
  breaks.value.splice(index, 1);
};

const submit = () => {
  form.post(route("gridslot.store"), {
    start_times: startTimes.value,
    end_times: endTimes.value,
    breaks: breaks.value,
  });
};

const calculateDuration = (index) => {
  const start = moment(startTimes.value[index], "HH:mm");
  const end = moment(endTimes.value[index], "HH:mm");
  const duration = moment.duration(end.diff(start));
  const hours = duration.hours();
  const minutes = duration.minutes();
  return `${hours} stunden ${minutes} minuten`;
};
</script>

<template>
  <Head title="Zeitfenster" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">
                Zeitfenster hinzufügen
                <span class="text-blue-500">
                  <Link :href="`/grid/${grid.id}`">{{ props.grid.name }}</Link>
                </span>
              </h2>
              <Link :href="route('grid.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-1 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="submit" class="mt-8">
                <div class="flex flex-col space-y-4">
                  <div v-for="(time, index) in startTimes" :key="index">
                    <div class="flex flex-col space-y-2">
                      <div class="flex flex-row items-center space-x-2">
                        <input
                          class="px-4 py-2 border border-gray-400 bg-white rounded-md w-full"
                          type="time"
                          :name="'start_times[' + index + ']'"
                          v-model="startTimes[index]"
                          required
                        />
                        <input
                          class="px-4 py-2 border border-gray-400 bg-white rounded-md w-full"
                          type="time"
                          :name="'end_times[' + index + ']'"
                          v-model="endTimes[index]"
                          required
                        />
                        <div class="relative">
                          <input
                            type="checkbox"
                            :name="'breaks[' + index + '][is_break]'"
                            v-model="breaks[index].is_break"
                            class="w-5 h-5 text-blue-500"
                          />
                          <div
                            class="hidden group-hover:block absolute z-10 bottom-full left-1/2 -ml-16 px-2 py-1 text-sm text-white bg-gray-800 rounded-md pointer-events-none"
                          >
                            {{ "Add Break" }}
                          </div>
                        </div>
                      </div>
                      <div class="flex flex-row justify-end">
                        <button
                          type="button"
                          class="text-red-400 text-sm"
                          @click="removeRow(index)"
                          v-if="startTimes.length > 1"
                        >
                           Entfernen
                        </button>
                      </div>
                    </div>
                    <div v-if="form.error" class="text-red-500">
                      {{ form.error }}
                    </div>
                  </div>
                  <button
                    type="button"
                    class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50"
                    @click="addRow"
                  >
                    Zeile hinzufügen
                  </button>
                </div>
                <div class="mt-3 text-right">
                  <SimpleSubmit :processing="form.processing" Submit />
                </div>
              </form>
            </div>
            <table class="table-auto w-full mt-1 text-p">
              <thead>
                <tr class="text-left font-bold">
                  <th class="px-4 py-2">Zeitraum</th>
                  <th class="px-4 py-2">Dauer</th>
                </tr>
              </thead>
              <tbody>
                <template
                  v-for="(time, index) in startTimes"
                  :key="`time_${index}`"
                >
                  <tr class="text-left">
                    <td class="border px-4 py-2">
                      {{ startTimes[index] }} - {{ endTimes[index] }}
                    </td>
                    <td class="border px-4 py-2">
                      {{ endTimes[index] ? calculateDuration(index) : "-" }}
                    </td>
                  </tr>
                  <tr v-if="breaks[index].is_break" :key="`break_${index}`">
                    <td class="px-4 py-2">Pause</td>
                    <td class="px-4 py-2"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
