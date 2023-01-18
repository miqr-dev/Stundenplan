<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    room: Object,
});

let form = useForm({
    name: props.room.name,
    room_number: props.room.room_number,
    floor: props.room.floor,
    capacity: props.room.capacity,
    id: props.room.id,
});

let update = () => {
    form.patch(`/room/${props.room.id}`);
};

let destroy = () => {
  if (confirm('Are you sure you want to delete this user?')) {
    form.delete(`/room/${props.room.id}`)
    console.log(`/room/${props.room.id}`)
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
                            <h2 class="text-h2">Edit the room name</h2>
                            <a href="#" class="text-blue-500 text-p" onclick="history.back();return false;">X</a>
                        </div>
                        <div
                            class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
                        >
                            <form @submit.prevent="update">
                                <div class="m-6">
                                    <label
                                        class="block mb-2  mt-4 text-xs font-bold text-gray-600 uppercase"
                                        for="name"
                                    >
                                        Room Name
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
                                    <label
                                        class="block mb-2  mt-4 text-xs font-bold text-gray-600 uppercase"
                                        for="room_number"
                                    >
                                        Room Number
                                    </label>
                                    <input
                                        v-model="form.room_number"
                                        class="w-full p-2 border border-gray-400 rounded"
                                        type="text"
                                        id="room_number"
                                        name="room_number"
                                    />
                                       <div
                                        v-if="form.errors.room_number"
                                        v-text="form.errors.room_number"
                                        class="text-red-500 text-sm mt-1"
                                    ></div>
                                    <label
                                        class="block mb-2  mt-4 text-xs font-bold text-gray-600 uppercase"
                                        for="floor"
                                    >
                                        floor
                                    </label>
                                    <input
                                        v-model="form.floor"
                                        class="w-full p-2 border border-gray-400 rounded"
                                        type="text"
                                        id="floor"
                                        name="floor"
                                    />
                                    <label
                                        class="block mb-2  mt-4 text-xs font-bold text-gray-600 uppercase"
                                        for="capacity"
                                    >
                                        Capacity
                                    </label>
                                    <input
                                        v-model="form.capacity"
                                        class="w-full p-2 border border-gray-400 rounded"
                                        type="text"
                                        id="capacity"
                                        name="capacity"
                                    />
                                    <div
                                        v-if="form.errors.capacity"
                                        v-text="form.errors.capacity"
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
