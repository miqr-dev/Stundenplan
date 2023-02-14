<script setup>

defineProps({
      headers: {
      type: Array,
      required: true
    },
     rows: {
      type: Array,
      required: true
    }
});
    const headers = ref(props.headers)
    const rows = ref(props.rows)
</script>


<template>

<table class="min-w-full leading-normal table" v-show="showLeaves">
  <thead>
    <tr>
      <th
        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
      >
        From
      </th>
      <th
        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
      >
        To
      </th>
      <th
        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
      >
        Status
      </th>
    </tr>
  </thead>
  <tbody>
    <tr
      v-for="leaves in teacher.teacher_not_available"
      :key="leaves.id"
    >
      <td
        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
      >
        <p class="text-gray-900 whitespace-no-wrap">
          {{ moment(leaves.awaystartdate).format("LL") }}
        </p>
        <p class="text-gray-600 whitespace-no-wrap">
          {{ moment(leaves.awaystartdate).week() }}
          &nbsp; KW
        </p>
      </td>
      <td
        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
      >
        <p class="text-gray-900 whitespace-no-wrap">
          {{ moment(leaves.awayenddate).format("LL") }}
        </p>
        <p class="text-gray-600 whitespace-no-wrap">
          {{ moment(leaves.awayenddate).week() }} &nbsp; KW
        </p>
      </td>
      <td
        class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
      >
        <span
          class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight"
        >
          <span
            aria-hidden
            class="absolute inset-0 bg-green-200 opacity-50 rounded-full"
          ></span>
          <span class="relative">Paid</span>
        </span>
      </td>
      <td
        class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right"
      >
        <button
          type="button"
          class="inline-block text-gray-500 hover:text-gray-700"
        >
          <svg
            class="inline-block h-6 w-6 fill-current"
            viewBox="0 0 24 24"
          >
            <path
              d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
            />
          </svg>
        </button>
      </td>
    </tr>
  </tbody>
</table>

</template>
