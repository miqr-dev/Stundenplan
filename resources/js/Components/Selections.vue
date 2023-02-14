<script setup>
import SelectionList from "@/components/SelectionList.vue";
import { ref, computed } from 'vue'

const assignments = ref([
  { name: "Finish project", complete: false, id: 1, tag:'Fosi'},
  { name: "Read Chapter 4", complete: false, id: 2, tag:'Osi'},
  { name: "Lorem ipsum dolor sit amet consectetur adipisicing elit.", complete: false, id: 3, tag:'Ibo'},
  { name: "Lorem ipsum dolor sit amet con.", complete: false, id: 4, tag:'Fosi'},
]);


const filters = computed(() => ({
  inProgress: assignments.value.filter((assignment) => !assignment.complete),
  completed: assignments.value.filter((assignment) => assignment.complete),
}));
</script>

<template>
  <section class="space-y-6">
    <SelectionList
      :assignments="filters.inProgress"
      title="In Progress"
    ></SelectionList>
    <SelectionList
      :assignments="filters.completed"
      title="Completed"
    ></SelectionList>
  </section>
</template>

