<template>
  <div class="mb-2">
    <div class="container mx-auto">
      <div class="py-2">
        <div class="flex justify-between">
          <div class="flex self-center">
            <button
              class="text-2xl font-semibold leading-tight hover:underline"
              @click="showItems = !showItems"
            >
              {{ title }}
            </button>
            <span class="ml-2 text-md text-blue-500 self-center"
              >({{ items.length }})</span
            >
            <div v-if="extraLink">
              <Link :href="extraLink.href" :class="extraLink.class">
                {{ extraLink.text }}
              </Link>
            </div>
          </div>
        </div>
        <div
          class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto"
          v-show="showItems"
        >
          <div
            class="inline-block min-w-full shadow-md rounded-lg overflow-hidden"
          >
            <table class="min-w-full leading-normal table">
              <thead>
                <tr>
                  <th
                    v-for="(header, index) in headers"
                    :key="index"
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                  >
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <slot name="table-body"></slot>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link} from "@inertiajs/vue3";
const showItems = ref(false);
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  },
  headers: {
    type: Array,
    required: true,
  },
  extraLink: {
    type: Object,
    default: null,
  },
});
</script>
