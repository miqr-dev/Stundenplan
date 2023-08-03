<script setup>
import { ref } from "vue";


// We define our props here
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  toggleSidebar: {
    type: Function,
    required: true
  },
  teachers: {
  type: Array,
  }
});
const closeSidebar = () => {
  props.toggleSidebar();
};
</script>

<template>
  <transition name="slide">
    <aside
      v-if="props.isOpen"  
      class="fixed top-0 right-0 h-full w-3/4 md:w-1/2 p-6 bg-white shadow-lg overflow-y-auto z-50">
      <!-- Close button -->
  <div class="flex justify-end">
    <button @click="closeSidebar" class="close-btn">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="red">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
      <!-- Here goes your sidebar content -->
<h2 class="text-xl font-semibold mb-4">Teachers</h2>
     <ul>
        <li v-for="teacher in props.teachers" :key="teacher.id">
          {{ teacher.name }} - {{ teacher.surname }}
        </li>
      </ul>

      <!-- ... -->
    </aside>
  </transition>
</template>



<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all .3s ease;
}
.slide-enter,
.slide-leave-to {
  transform: translateX(100%);
}

.close-btn {
  cursor: pointer;
  background: none;
  border: none;
}
</style>
