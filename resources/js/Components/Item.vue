<template>
  <li @click="onSelected">
    <input type="checkbox" :checked="isSelected" @click.stop>
    <span>{{ item.name }}</span>
  </li>
</template>

<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
  props: {
    item: Object,
    isSelected: Boolean,
  },
  emits: ['selected'],
  setup(props) {
    const checkboxValue = ref(props.isSelected);

    function onSelected() {
      checkboxValue.value = !checkboxValue.value;
      const selectedEvent = { id: props.item.id, value: checkboxValue.value };
      props.$emit('selected', selectedEvent);
    }

    return {
      onSelected,
      checkboxValue,
    };
  },
});
</script>