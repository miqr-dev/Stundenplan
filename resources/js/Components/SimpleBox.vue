<template>
  <div class="multiselect">
    <div class="selected-box">
      <h3>Selected Items</h3>
      <ul>
        <li v-for="item in selectedItems" :key="item.id" @click="toggleItem(item)">
          {{ item.name }}
        </li>
      </ul>
    </div>
    <div class="available-box">
      <h3>Available Items</h3>
      <ul>
        <li v-for="item in availableItems" :key="item.id" @click="toggleItem(item)">
          {{ item.name }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, watchEffect } from "vue";

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
  selectedItems: {
    type: Array,
    required: true,
  },
});

const availableItems = ref(props.items);

function toggleItem(item) {
  const index = props.selectedItems.findIndex(i => i.id === item.id);
  if (index === -1) {
    availableItems.value.splice(availableItems.value.indexOf(item), 1);
    props.selectedItems.push(item);
  } else {
    props.selectedItems.splice(index, 1);
    availableItems.value.push(item);
  }
}

watchEffect(() => {
  availableItems.value.sort((a, b) => a.name.localeCompare(b.name));
  props.selectedItems.sort((a, b) => a.name.localeCompare(b.name));
});
</script>

<style scoped>
.multiselect {
  display: flex;
  justify-content: space-between;
}

.selected-box,
.available-box {
  width: 45%;
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

li {
  margin-bottom: 5px;
  cursor: pointer;
}

.selected-box li {
  color: blue;
}

button {
  margin-right: 5px;
}
</style>
