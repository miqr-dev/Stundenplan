<template>
  <div class="dual-list">
    <div class="dual-list-search">
      <input type="text" placeholder="Search" v-model="leftSearch" />
    </div>
    <div class="dual-list-container">
      <div class="dual-list-box">
        <div class="dual-list-header">
          <div class="dual-list-title">Available Items</div>
        </div>
        <ul>
          <li v-for="item in filteredLeftItems" :key="item.id">
            <label>
              <input type="checkbox" :value="item.id" v-model="leftSelectedIds" />
              {{ item.name }}
            </label>
          </li>
          <li v-if="filteredLeftItems.length === 0">No items found.</li>
        </ul>
      </div>
      <div class="dual-list-controls">
        <button @click="moveRight" :disabled="leftSelectedIds.length === 0">Select</button>
        <button @click="moveLeft" :disabled="rightSelectedIds.length === 0">Deselect</button>
      </div>
      <div class="dual-list-box">
        <div class="dual-list-header">
          <div class="dual-list-title">Selected Items</div>
        </div>
        <ul>
          <li v-for="item in filteredRightItems" :key="item.id">
            <label>
              <input type="checkbox" :value="item.id" v-model="rightSelectedIds" />
              {{ item.name }}
            </label>
          </li>
          <li v-if="filteredRightItems.length === 0">No items selected.</li>
        </ul>
      </div>
    </div>
    <div class="dual-list-search">
      <input type="text" placeholder="Search" v-model="rightSearch" />
    </div>
  </div>
</template>
<script setup>
import { computed, defineEmits, defineProps } from 'vue';
import Item from '@/Components/Item.vue';

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  leftSelected: {
    type: Array,
    default: () => []
  },
  rightSelected: {
    type: Array,
    default: () => []
  },
});

const emits = defineEmits(['update:leftSelected', 'update:rightSelected']);

const filteredLeftItems = computed(() => {
  return props.items.filter(item => {
    return !props.rightSelected.includes(item) && !props.leftSelected.includes(item);
  });
});

const filteredRightItems = computed(() => {
  return props.items.filter(item => {
    return !props.leftSelected.includes(item) && !props.rightSelected.includes(item);
  });
});

function moveRight() {
  emits('update:rightSelected', [...props.rightSelected, ...props.leftSelected]);
  emits('update:leftSelected', []);
}

function moveLeft() {
  emits('update:leftSelected', [...props.leftSelected, ...props.rightSelected]);
  emits('update:rightSelected', []);
}
</script>
<style scoped>
.dual-listbox {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

ul {
  list-style: none;
  padding: 0;
}

li {
  cursor: pointer;
  margin: 5px 0;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

li.selected {
  background-color: #e0e0e0;
}

.left,
.right {
  width: 45%;
}

.center {
  width: 10%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

button {
  margin: 10px;
}
</style>
