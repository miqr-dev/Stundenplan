<script setup>
import Select from "@/components/Select.vue";
import {computed } from "vue";

  defineProps({
    assignments: Array,
    title: String,
  });
  

tags = computed(() => {
  return assignments.value.map((a) => a.tag);
});

</script>

<template>
  <section v-show="assignments.length">
    <h2 class="font-bold mb-2">
      {{ title }}
      <span>({{ assignments.length }})</span>
    </h2>

    <div class="flex gap-2">
      <button v-for="tag in tags" class="border rounded px-1 py-px text-xs">{{ tag }}</button>
    </div>

    <ul class="border border-gray-600 divide-y mt-6">
      <Select
        v-for="assignment in assignments"
        :key="assignment.id"
        :assignment="assignment"
      ></Select>
    </ul>
  </section>
</template>



