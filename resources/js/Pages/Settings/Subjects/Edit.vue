<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import { reactive, ref, computed } from "vue";
import { EllipsisHorizontalIcon } from "@heroicons/vue/20/solid";
import SimpleInput from "@/Components/SimpleInput.vue";
import SimpleDelete from "@/Components/SimpleDelete.vue";
import SimpleSubmit from "@/Components/SimpleSubmit.vue";
import SimpleItemSelector from "@/Components/SimpleItemSelector.vue";

const props = defineProps({
  subject: Object,
  templates: Array,
});

const form = useForm({
  id: props.subject.id,
  name: props.subject.name,
  color: props.subject.color,
  templates: props.subject.templates,
});

const getTemplateName = (templateId) => {
  const template = props.templates.find((t) => t.id === templateId);
  return template ? template.name : "";
};

const update = () => {
  form.put(route("subject.update", form.id), {
    onSuccess: () => form.reset(),
  });
};

const destroy = () => {
  if (confirm("Are you sure you want to delete this subject?")) {
    form.delete(route("subject.destroy", form.id), {
      onSuccess: () => {
        form.reset();
        $inertia.visit(route("subject.index"));
      },
    });
  }
};
</script>

<template>
  <Head title="Subjects" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Edit the subject</h2>
              <Link :href="route('subject.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="update">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>

                <!-- implement the vue-swatch here -->
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="maxhourweek"
                  >
                    Color
                  </label>
                  <VSwatches
                    v-model="form.color"
                    swatches="text-advanced"
                    row-length="12"
                    show-border
                    popover-x="button"
                  ></VSwatches>
                  <div
                    v-if="form.errors.color"
                    v-text="form.errors.color"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <SimpleItemSelector
                  v-model="form.templates"
                  :items="props.templates"
                  :get-item-name="getTemplateName"
                  :searchable="true"
                  label="Temp"
                ></SimpleItemSelector>

                <!-- implement the vue-swatch here -->
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Delete Subject" />
                  <SimpleSubmit :processing="form.processing" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>


<style scoped>
.Vswatches__wrapper {
  padding: 0;
}
</style>