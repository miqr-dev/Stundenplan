<script setup>

function tags() 
</script>

<template>
  <div class="flex gap-2">
    <button v-for="tag in tags" @click="currentTag = tag" class="border rounded px-1 py-px text-xs">
      {{ tag }}
    </button>
  </div>
</template>