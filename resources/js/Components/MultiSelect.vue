<template>
  <div>
    <div class="left-box">
      <h3>Available subjects</h3>
      <ul>
        <li v-for="subject in availableSubjects" :key="subject.id">
          <input type="checkbox" :value="subject.id" v-model="selectedSubjects"/>
          {{ subject.name }}
        </li>
      </ul>
    </div>
    <div class="right-box">
      <h3>Selected subjects</h3>
      <ul>
        <li v-for="subject in selectedSubjects" :key="subject">
          {{ subjects.find(s => s.id === subject).name }}
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import { ref, computed } from 'vue';

export default {
  props: {
    subjects: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const selectedSubjects = ref([]);
    
    const availableSubjects = computed(() => {
      return props.subjects.filter(subject => !selectedSubjects.value.includes(subject.id));
    });

    return {
      selectedSubjects,
      availableSubjects
    }
  }
}
</script>