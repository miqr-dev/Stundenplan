<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
  links: Array,
});


</script>

<template>
  <div>
    <Component
      :is="link.url ? Link : 'span'"
      v-for="link in links"
      :href="link.url"
      v-html="link.label"
      class="mr-2 text-sm"
      :class="{ 'text-gray-500' : !link.url, 'text-blue-500' : link.active }"
    />
  </div>
</template>

