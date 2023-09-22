<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";

const props = defineProps({
  grid: Object,
  gridslots: Array,
});

const form = useForm({
  start_times: props.gridslots.map((gridslot) => gridslot.start_time),
  end_times: props.gridslots.map((gridslot) => gridslot.end_time),
  is_breaks: props.gridslots.map((gridslot) => gridslot.is_break),
});

const update = () => {
  form.patch(route("grid.update", { grid: props.grid.id }));
};
</script>

<template>
  <Head title="Gridslots" />

  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-screen-xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu />
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-screen-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-full sm:w-3/4 md:w-2/3 lg:w-3/5 xl:w-1/2 shadow-md mx-auto">
            <div class="flex justify-between">
              <h2 class="text-h2">
                Gridslots bearbeiten
                <span class="text-blue-500">
                  <Link :href="`/grid/${grid.id}`">{{ props.grid.name }}</Link>
                </span>
              </h2>
              <SimpleBack />
            </div>
            <div class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg">
              <form @submit.prevent="update" class="mt-8">
                <table class="table-auto w-full mt-1 text-p text-center">
                  <thead>
                    <tr class="text-left font-bold">
                      <th class="px-4 py-2">Startzeit</th>
                      <th class="px-4 py-2">Endzeit</th>
                      <th class="px-4 py-2">Pause?</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class="text-left"
                      v-for="(gridslot, index) in gridslots"
                      :key="gridslot.id"
                    >
                      <td class="border px-4 py-2">
                        <input
                          class="px-4 py-2 border border-gray-400 bg-white rounded-md w-full"
                          type="time"
                          v-model="form.start_times[index]"
                          required
                        />
                      </td>
                      <td class="border px-4 py-2">
                        <input
                          class="px-4 py-2 border border-gray-400 bg-white rounded-md w-full"
                          type="time"
                          v-model="form.end_times[index]"
                          required
                        />
                      </td>
                      <td class="border px-4 py-2">
                        <input
                          type="checkbox"
                          v-model="form.is_breaks[index]"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="mt-3 text-right">
                  <SimpleSubmit :processing="form.processing">Aktualisieren</SimpleSubmit>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
