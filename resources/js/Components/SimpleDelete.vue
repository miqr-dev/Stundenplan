<script setup>
import { ref } from 'vue';
import ConfirmationModal from '@/Components/SimpleConfirmationModal.vue';

const props = defineProps({
  buttonText: {
    type: String,
    default: 'Löschen',
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
      title="Löschbestätigung"
      message="Sind Sie sicher, dass Sie löschen möchten"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </div>
</template>