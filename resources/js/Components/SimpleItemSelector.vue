<template>
  <div class="mb-6 flex w-full space-x-3">
    <div class="w-1/2">
      <label class="block mb-2 text-xs font-bold text-gray-600 uppercase">{{
        label
      }}</label>
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
          v-for="template in filteredItems"
          :key="template.id"
          :class="{
            'bg-blue-400 text-white': modelValue.includes(template.id),
          }"
          class="flex items-center px-4 py-2 mr-2 mb-2 text-sm font-medium rounded-lg cursor-pointer mt-2 bg-white hover:bg-gray-100 hover:text-blue-400 transition-colors"
          @click="toggleItem(template.id)"
        >
          {{ template.name }}
        </div>
      </div>
      <div
        v-if="errors"
        v-text="errors"
        class="text-red-500 text-sm mt-1"
      ></div>
    </div>
    <div class="w-1/2">
      <label
        v-if="modelValue.length > 0"
        class="block text-sm font-bold text-gray-600 uppercase mb-6 p-3"
      >
        Included in
        <span class="text-blue-500">({{ modelValue.length }})</span> {{ label }}
      </label>
      <div class="overflow-auto h-72 p-4 text-left">
        <label
          v-if="modelValue.length === 0"
          class="block text-sm font-bold text-gray-600 uppercase mb-6"
        >
          <span class="text-red-300">{{ itemName }}</span> doesn't include in
          any {{ label }}
        </label>
        <div v-else>
          <div
            class="flex justify-between"
            v-for="(itemId, index) in modelValue"
            :key="`${index}-${itemId}`"
          >
            <div
              class="inline-flex items-center px-4 mr-2 mb-2 text-p-gray text-sm font-medium rounded-lg cursor-pointer"
            >
              {{ index + 1 }}. {{ getItemName(itemId).name }}
            </div>
            <div class="relative">
              <button
                class="focus:outline-none"
                @click.prevent="toggleDropdown(index)"
              >
                <EllipsisHorizontalIcon class="h-6 w-6 text-gray-500" />
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
                        @click="toggleItem(itemId)"
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
                        :href="`/template/${itemId}/edit`"
                      >
                        Edit Template
                      </Link>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { EllipsisHorizontalIcon } from "@heroicons/vue/20/solid";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  label: String,
  items: Array,
  modelValue: Array,
  itemName: String,
  errors: String,
});

const emit = defineEmits(["update:modelValue"]);

const search = ref("");

const filteredItems = computed(() => {
  if (!search.value) {
    return props.items;
  }
  return props.items.filter((item) =>
    item.name.toLowerCase().includes(search.value.toLowerCase())
  );
});

const dropdownIndex = ref(null);

const getItemName = (itemId) => {
  const item = props.items.find((item) => item.id === itemId);
  return item ? { id: itemId, name: item.name } : { id: "", name: "" };
};

const toggleItem = (itemId) => {
  const index = props.modelValue.indexOf(itemId);
  if (index === -1) {
    props.modelValue.push(itemId);
  } else {
    props.modelValue.splice(index, 1);
  }
  emit("update:modelValue", props.modelValue);
};

const toggleDropdown = (index) => {
  if (dropdownIndex.value === index) {
    dropdownIndex.value = null;
  } else {
    dropdownIndex.value = index;
  }
};
</script>
<style scoped>
.item {
  background-color: white;
  transition: background-color 0.3s ease;
}

.item:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.item.selected {
  background-color: #3b82f6;
  color: white;
}

.item.selected:hover {
  background-color: #2563eb;
}
</style>
