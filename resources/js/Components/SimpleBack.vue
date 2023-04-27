<script setup>
const goBack = () => {
  history.back();return false;
};
</script>

<template>
  <a
    href="#"
    @click.prevent="goBack"
    class="text-blue-500 text-p"
  >
    X
  </a>
</template>

