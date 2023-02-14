<template>
  <section v-show="selections.length">
    <h2 class="font-bold mb-2">{{ title }}</h2>
    <div class="w-1/2 bg-white rounded-lg p-2">
      <input
        v-model="searchText"
        class="p-2 rounded-lg"
        placeholder="Search subjects"
        type="text"
      />
      <ul class="list-none">
        <li
          v-for="subj in selections"
          :key="subj.id"
          @click="selectSubject(subj)"
          class="p-2 cursor-pointer hover:bg-gray-200"
        >
          {{ subj.name }}
        </li>
      </ul>
    </div>
  </section>
</template>

<script setup>
defineProps({
  selections: Array,
  title: String,
  search: String
});


function selectSubject(subject) {
  selectedSubjects.value = [...selectedSubjects.value, subject].sort(
    (a, b) => a.id - b.id
  );
}
</script>