<template>
  <div>
    <template v-if="isEditing(itemId)">
      <input
        ref="inputField"
        type="number"
        :value="getSollValue(itemId)"
        @input="updateSollValue(itemId, $event.target.value)"
        @blur="setEditing(itemId, false)"
        @dblclick.stop
        @click.stop
        class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 block sm:text-sm border rounded-md input-fixed-width"
        placeholder="Soll"
        autofocus
      />
    </template>
    <span
      v-else
      class="text-emerald-600"
      @dblclick="setEditing(itemId, true)"
    >
      {{ getSollValue(itemId) }}
    </span>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, inject } from "vue";

const props = defineProps({
  itemId: Number,
  sollValues: Array,
  items: Array,
});

const emit = defineEmits(['update:sollValues']);

const getSollValue = (subjectId) => {
  const subject = props.sollValues.find((s) => s.subject_id === subjectId);
  if (subject) {
    return subject.soll;
  } else {
    const defaultSubject = props.items.find((item) => item.id === subjectId);
    return defaultSubject ? defaultSubject.default_soll : '';
  }
};

const updateSollValue = (subjectId, sollValue) => {
  const index = props.sollValues.findIndex((s) => s.subject_id === subjectId);
  if (index !== -1) {
    props.sollValues[index].soll = sollValue;
  } else {
    props.sollValues.push({ subject_id: subjectId, soll: sollValue });
  }
  emit('update:sollValues', props.sollValues);
};

const editing = ref({});

const isEditing = (itemId) => {
  return editing.value[itemId] === undefined ? false : editing.value[itemId];
};

const setEditing = (itemId, value) => {
  editing.value[itemId] = value;
};
</script>


<style scoped>

.input-fixed-width {
  width: calc(1ch * 8 + 2px); /* 6 characters width + 2px for border */
  text-align: center;
}
</style>