<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    location: Object,
});

let form = useForm({
    name: props.location.name,
    id: props.location.id,
});

let update = () => {
    form.patch(`/location/${props.location.id}`);
};

let destroy = () => {
  if (confirm('Are you sure you want to delete this user?')) {
    form.delete(`/location/${props.location.id}`)
    console.log(`/location/${props.location.id}`)
  }
}
</script>

<template>
    <Head title="Cities" />

    <BreezeAuthenticatedLayout>
        <header class="bg-gray-100 shadow">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <SettingsSubMenu></SettingsSubMenu>
            </div>
        </header>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-100 overflow-hidden rounded-lg">
                    <div
                        class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
                    >
                        <div class="flex justify-between">
                            <h2 class="text-h2">Edit the location name</h2>
                            <Link
                                :href="route('location.index')"
                                class="text-blue-500 text-p"
                                >X</Link
                            >
                        </div>
                        <div
                            class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
                        >
                            <form @submit.prevent="update">
                                <div class="mb-6">
                                    <label
                                        class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                                        for="name"
                                    >
                                        Name
                                    </label>
                                    <input
                                        v-model="form.name"
                                        class="w-full p-2 border border-gray-400 rounded"
                                        type="text"
                                        id="name"
                                        name="name"
                                    />
                                    <div
                                        v-if="form.errors.name"
                                        v-text="form.errors.name"
                                        class="text-red-500 text-sm mt-1"
                                    ></div>
                                </div>
                                <div class="mb-1 flex justify-between">
                                    <button
                                        type="submit"
                                        class="text-blue-600 hover:text-blue-900"
                                        :class="{
                                            'cursor-not-allowed':
                                                form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Submit
                                    </button>
                                    <button
                                        class="text-orange-600 hover:text-orange-900"
                                        tabindex="-1"
                                        type="button"
                                        @click="destroy"
                                    >
                                        Delete City
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
