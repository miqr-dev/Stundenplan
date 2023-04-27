<!-- DeleteButton.vue -->
<script setup>
import { ref } from 'vue';
import ConfirmationModal from '@/Components/SimpleConfirmationModal.vue';

const props = defineProps({
  buttonText: {
    type: String,
    default: 'Delete',
  },
});

const emits = defineEmits(['click']);

const showModal = ref(false);

const handleDelete = () => {
  showModal.value = true;
};

const handleConfirm = () => {
  showModal.value = false;
  emits('click');
};

const handleCancel = () => {
  showModal.value = false;
};
</script>

<template>
  <div>
    <button class="text-orange-600 hover:text-orange-900" type="button" @click="handleDelete">
      {{ buttonText }}
    </button>
    <ConfirmationModal
      :isVisible="showModal"
      title="Delete Confirmation"
      message="Are you sure you want to delete this item?"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </div>
</template>
