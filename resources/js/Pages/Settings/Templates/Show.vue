<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import SimpleCollapsibleTable from "@/Components/SimpleCollapsibleTable.vue";
import SimpleCustomRow from "@/Components/SimpleCustomRow.vue";

const props = defineProps({
  template: {},
});
</script>

<template>
  <Head title="Template Show" />
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
                {{ template.name }}
              </h2>
              <SimpleBack />
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <label
                class="block mb-2 text-xs font-bold text-gray-600 uppercase mt-4"
              >
                Subjects:
              </label>
              <SimpleCollapsibleTable
                title="Subjects"
                :items="template.subjects"
                :headers="['name', 'soll', 'color']"
              >
                <template #table-body>
                  <SimpleCustomRow
                    v-for="subject in template.subjects"
                    :key="subject.id"
                    :item="subject"
                    :columns="[
                      {
                        type: 'text',
                        value: subject.name,
                      },
                      {
                        type: 'text',
                        value: subject.default_soll,
                      },
                      { type: 'swatches', value: subject.color },
                    ]"
                  />
                </template>
              </SimpleCollapsibleTable>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
