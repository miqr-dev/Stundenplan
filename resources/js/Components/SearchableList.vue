<template>
  <div class="msl-searchable-list">
    <input
      v-if="!hideSearchInput"
      v-model="debouncedSearchText"
      class="msl-search-list-input"
      :class="searchInputClass"
      :placeholder="placeholderText"
    >
    <div class="msl-searchable-list__items"
    :class="{ 'msl-searchable-list__items--disabled': disabled || readOnly }">
      <div
        v-for="(option, index) in filteredListItems"
        :key="index"
        class="msl-searchable-list__item"
        :class="{'msl-searchable-list__item--disabled': option.disabled || disabled || readOnly, [highlightClass]: highlightDiff && highlightedItemsMap[getValue(option)] }"
        @click="clickOption(option)"
      >
        {{ getOptionDisplay(option) }}
      </div>

      <div
        v-if="noItems"
        class="msl-searchable-list__no-item"
      >
        {{ noOptionsText }}
      </div>

      <div
        v-if="noFilteredItems"
        class="msl-searchable-list__no-item"
      >
        {{ noItemsFoundText }}
      </div>
    </div>
  </div>
</template>

<script>
import debounce from 'lodash/debounce';
import { ref, computed, watch } from 'vue';

export default {
  name: 'SearchableList',
  props: {
    listItems: {
      type: Array,
      default() {
        return [];
      },
      required: true,
    },
    selectedListItems: {
      type: Array,
      default() {
        return [];
      },
    },
    highlightItems: {
      type: Array,
      default() {
        return [];
      },
    },
    placeholderText: {
      type: String,
      default: 'Search',
    },
    displayProperty: {
      type: Function,
      default: (value) => value,
    },
    valueProperty: {
      type: Function,
      default: (value) => value,
    },
    noOptionsText: {
      type: String,
      default: 'No options',
    },
    searchInputClass: {
      type: String,
      default: '',
    },
    noItemsFoundText: {
      type: String,
      default: 'No options found',
    },
    highlightDiff: {
      type: Boolean,
      default: false,
    },
    highlightClass: {
      type: String,
      default: '',
    },
    hideSearchInput: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    readOnly: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
const searchText = ref('');
const debouncedSearchText = ref(searchText.value);
function getValue(item, valueProperty) {
  return valueProperty(item);
}
function convertArrayToMap(items, valueProperty) {
  return items.reduce((result, item) => {
    const value = getValue(item, valueProperty);
    result[value] = true;
    return result;
  }, {});
}

watch(searchText, debounce((newValue) => {
  debouncedSearchText.value = newValue;
}, 500));

const noItems = computed(() => {
  return (props.selectedListItems.length && props.availableItems.length < 1)
        || (!props.listItems || props.listItems.length < 1);
});

const noFilteredItems = computed(() => {
  return props.availableItems
  && props.availableItems.length > 0
  && (!props.filteredListItems || props.filteredListItems.length < 1);
});

const highlightedItemsMap = computed(() => {
  return convertArrayToMap(props.highlightItems, props.valueProperty);
});

function clickOption(option) {
  // implementation for handling option clicks
}

function getOptionDisplay(option) {
  return props.displayProperty(option);
}

return {
  debouncedSearchText,
  noItems,
  noFilteredItems,
  highlightedItemsMap,
  clickOption,
  getOptionDisplay,
}
}
}
</script>
