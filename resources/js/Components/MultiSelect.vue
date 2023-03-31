<script setup>
import { ref, computed, watch, defineEmit } from 'vue';

const props = defineProps({
  title: String,
  searchPlaceholder: String,
  items: {
    type: Array,
    required: true,
  },
  itemKey: {
    type: String,
    default: 'id',
  },
  itemLabel: {
    type: String,
    default: 'name',
  },
  selectedItems: Array,
  showDropdown: {
    type: Boolean,
    default: true,
  },
});

const search = ref('');
const dropdownIndex = ref(null);

const filteredItems = computed(() => {
  if (!search.value) {
    return props.items;
  }
  return props.items.filter((item) =>
    item[props.itemLabel].toLowerCase().includes(search.value.toLowerCase())
  );
});

const toggleItem = (itemKey) => {
  const index = props.selectedItems.indexOf(itemKey);
  if (index === -1) {
    props.selectedItems.push(itemKey);
  } else {
    props.selectedItems.splice(index, 1);
  }
};

const isSelected = (itemKey) => {
  return props.selectedItems.includes(itemKey);
};

const getItemBy = (itemKey) => {
  const item = props.items.find((item) => item[props.itemKey] === itemKey);
  return item ? item[props.item.Label] : '';
};
const toggleDropdown = (index) => {
dropdownIndex.value = dropdownIndex.value === index ? null : index;
};

const emit = defineEmit(['update:selectedItems']);
watch(
() => props.selectedItems,
(value) => {
emit('update:selectedItems', value);
},
{ deep: true }
);
</script>


<template>
  <div class="mb-6 flex w-full space-x-3">
    <div class="w-1/2">
      <label
        class="block mb-2 text-xs font-bold text-gray-600 uppercase"
        :for="inputId"
      >
        {{ label }}
      </label>
      <input
        type="text"
        v-model="search"
        class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block sm:text-sm border rounded-md mb-2 w-full"
                placeholder="Search items"
        :id="inputId"
      />
      <div
        class="overflow-auto h-72 border-solid border-2 border-gray-300 rounded-xl px-4 py-2"
      >
        <div
          v-for="item in filteredItems"
          :key="item.id"
          :class="{
            'bg-blue-400 text-white': selectedItems.includes(item.id),
          }"
          class="flex items-center px-4 py-2 mr-2 mb-2 text-sm font-medium rounded-lg cursor-pointer mt-2 bg-white"
          @click="toggleItem(item.id)"
        >
          {{ item.name }}
        </div>
      </div>
      <div
        v-if="errors         && errors[errorKey]"
        v-text="errors[errorKey]"
        class="text-red-500 text-sm mt-1"
      ></div>
    </div>
    <div class="w-1/2">
      <label
        v-if="selectedItems.length > 0"
        class="block mb-2 text-sm font-bold text-gray-600 uppercase mb-6 p-3"
      >
        Included in
        <span class="text-blue-500"
          >({{ selectedItems.length }})</span
        >
        Items
      </label>
      <div class="overflow-auto h-72 p-4 text-left">
        <label
          v-if="selectedItems.length === 0"
          class="block mb-2 text-sm font-bold text-gray-600 uppercase mb-6"
        >
          <span           class="text-red-300"> {{ itemName }}</span>
          doesn't include in any Item
        </label>
        <div v-else>
          <div
            class="flex justify-between"
            v-for="(itemId, index) in selectedItems"
            :key="`${index}-${itemId}`"
          >
            <div
              class="inline-flex items-center px-4 mr-2 mb-2 text-p-gray text-sm font-medium rounded-lg cursor-pointer text-white"
            >
              {{ index + 1 }}.
              {{ getItemName(itemId).name }}
            </div>
            <slot name="actions" :itemId="itemId" :index="index"></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



       
