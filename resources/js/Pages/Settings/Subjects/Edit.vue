<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import { reactive, ref, computed } from "vue";
import { EllipsisHorizontalIcon } from "@heroicons/vue/20/solid";

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

const search = ref("");
const dropdownIndex = ref(null);

const toggleTemplate = (templateId) => {
  const index = form.templates.indexOf(templateId);
  if (index === -1) {
    form.templates.push(templateId);
  } else {
    form.templates.splice(index, 1);
  }
  dropdownIndex.value = null;
};

const toggleDropdown = (index) => {
  dropdownIndex.value = dropdownIndex.value === index ? null : index;
  // close dropdown when clicked outside
  if (dropdownIndex.value !== null) {
    window.addEventListener("click", closeDropdown);
  } else {
    window.removeEventListener("click", closeDropdown);
  }
};

const closeDropdown = (event) => {
  // check if clicked outside the dropdown element
  if (!event.target.closest(".relative")) {
    dropdownIndex.value = null;
    window.removeEventListener("click", closeDropdown);
  }
};

const filteredTemplates = computed(() => {
  if (!search.value) {
    return props.templates;
  }
  return props.templates.filter((template) =>
    template.name.toLowerCase().includes(search.value.toLowerCase())
  );
});

const getTemplateName = (templateId) => {
  const template = props.templates.find(
    (template) => template.id === templateId
  );
  return template
    ? { id: templateId, name: template.name }
    : { id: "", name: "" };
};

const update = () => {
  form.patch(route("subject.update", form.id));
};

const destroy = () => {
  form.delete(route("subject.destroy", form.id));
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

                <div class="mb-6 flex w-full space-x-3">
                  <div class="w-1/2">
                    <label
                      class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    >
                      Templates
                    </label>
                    <input
                      type="text"
                      v-model="search"
                      class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block sm:text-sm border rounded-md mb-2 w-full"
                      placeholder="Search templates"
                    />
                    <div
                      class="overflow-auto h-72 border-solid border-2 border-gray-300 rounded-xl px-4 py-2"
                    >
                      <div
                        v-for="template in filteredTemplates"
                        :key="template.id"
                        :class="{
                          'bg-blue-400 text-white': form.templates.includes(
                            template.id
                          ),
                        }"
                        class="flex items-center px-4 py-2 mr-2 mb-2 text-sm font-medium rounded-lg cursor-pointer mt-2 bg-white"
                        @click="toggleTemplate(template.id)"
                      >
                        {{ template.name }}
                      </div>
                    </div>
                    <div
                      v-if="form.errors.templates"
                      v-text="form.errors.templates"
                      class="text-red-500 text-sm mt-1"
                    ></div>
                  </div>
                  <div class="w-1/2">
                    <label
                      v-if="form.templates.length > 0"
                      class="block text-sm font-bold text-gray-600 uppercase mb-6 p-3"
                    >
                      Included in
                      <span class="text-blue-500"
                        >({{ form.templates.length }})</span
                      >
                      Tempaltes
                    </label>
                    <div class="overflow-auto h-72 p-4 text-left">
                      <label
                        v-if="form.templates.length === 0"
                        class="block text-sm font-bold text-gray-600 uppercase mb-6"
                      >
                        <span class="text-red-300"> {{ form.name }}</span>
                        doesn't include in any Template
                      </label>
                      <div v-else>
                        <div
                          class="flex justify-between"
                          v-for="(templateId, index) in form.templates"
                          :key="`${index}-${templateId}`"
                        >
                          <div
                            class="inline-flex items-center px-4 mr-2 mb-2 text-p-gray text-sm font-medium rounded-lg cursor-pointer"
                          >
                            {{ index + 1 }}.
                            {{ getTemplateName(templateId).name }}
                          </div>
                          <div class="relative">
                            <button
                              class="focus:outline-none"
                              @click.prevent="toggleDropdown(index)"
                            >
                              <EllipsisHorizontalIcon
                                class="h-6 w-6 text-gray-500"
                              />
                            </button>
                            <template v-if="dropdownIndex === index">
                              <div
                                ref="dropdown"
                                class="absolute right-0 top-full mt-2 w-56 z-10 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                              >
                                <div
                                  role="menu"
                                  aria-orientation="vertical"
                                  aria-labelledby="options-menu"
                                >
                                  <div
                                    class="flex items-center px-4 py-2 text-sm font-medium hover:bg-gray-400 hover:text-white"
                                    role="menuitem"
                                  >
                                    <button
                                      class="block w-full text-left"
                                      @click="
                                        toggleTemplate(
                                          getTemplateName(templateId).id
                                        )
                                      "
                                    >
                                      Deselect
                                    </button>
                                  </div>
                                  <div
                                    class="flex items-center px-4 py-2 text-sm font-medium hover:bg-gray-400 hover:text-white"
                                    role="menuitem"
                                  >
                                    <Link
                                      class="block w-full text-left"
                                      :href="`/template/${templateId}/edit`"
                                    >
                                      Edit Template
                                    </Link>
                                  </div>
                                  <!-- <div
                                    class="flex items-center px-4 py-2 text-sm font-medium"
                                    role="menuitem"
                                  >
                                    <span class="mr-2 font-bold"
                                      >Template Name:</span
                                    >
                                    {{ getTemplateName(templateId).name }}
                                  </div> -->
                                </div>
                              </div>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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



