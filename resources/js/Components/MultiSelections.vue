<script setup>
import { ref, computed, reactive, onMounted } from 'vue'

 const props = defineProps({
    city: Array,
  });

  onMounted(() => {
  console.log(props.city)
})

//     const selectedSubjects = ref([])
//     const searchText = ref('')
//     const searchSelectedText = ref('')

//  const filteredSubjects = computed(() => {
//   return city && city.filter(subj =>
//     !selectedSubjects.value.find(s => s.id === subj.id) &&
//     subj.name.toLowerCase().includes(searchText.value.toLowerCase())
//   ).sort((a, b) => a.id - b.id)
// })

//     const filteredSelectedSubjects = computed(() => {
//       return selectedSubjects.value.filter(subj =>
//         subj.name.toLowerCase().includes(searchSelectedText.value.toLowerCase())
//       ).sort((a, b) => a.id - b.id)
//     })

//     function selectSubject(subject) {
//       selectedSubjects.value = [...selectedSubjects.value, subject].sort((a, b) => a.id - b.id)
//     }

//     function deselectSubject(subject) {
//       selectedSubjects.value = selectedSubjects.value.filter(subj => subj.id !== subject.id)
//     }

//     function consoleSelectedSubjects() {
//       console.log(selectedSubjects.value)
//     }
    </script>

<template>
  <!-- <div class="flex text-gray-900">
    <div class="w-1/2 bg-white rounded-lg p-2">
      <input v-model="searchText" class="p-2 rounded-lg" placeholder="Search subjects" type="text">
      <ul class="list-none">
        <li v-for="subj in filteredSubjects" :key="subj.id" @click="selectSubject(subj)" class="p-2 cursor-pointer hover:bg-gray-200">{{subj.name}}</li>
      </ul>
    </div>
    <div class="w-1/2 bg-white rounded-lg p-2">
      <input v-model="searchSelectedText" class="p-2 rounded-lg" placeholder="Search selected subjects" type="text">
      <ul class="list-none">
        <li v-for="subj in filteredSelectedSubjects" :key="subj.id" @click="deselectSubject(subj)" class="p-2 cursor-pointer hover:bg-gray-200">{{subj.name}}</li>
      </ul>
    </div>
  </div>
  <div class="text-right">
    <button class="bg-blue-500 px-4 py-2 rounded-lg text-white" @click="consoleSelectedSubjects">
      Log selected subjects
    </button>
  </div> -->
</template>

