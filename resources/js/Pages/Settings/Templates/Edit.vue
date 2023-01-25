<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";



const props = defineProps({
    template: Object,
    subjects: Array,
});

let form = useForm({
    id: props.template.id,
    name: props.template.name,
    subjects: props.template.subjects,
});
const handleChange = () => {};

const update = async () => {
    if (form.id) {
        await form.patch(route("template.update", form.id));
    }
};



</script>
<template>
    <Head title="Template Edit" />
    <BreezeAuthenticatedLayout>
        <header class="bg-gray-100 shadow">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <SettingsSubMenu></SettingsSubMenu>
            </div>
        </header>
        <div class="py-12">
            <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-100 overflow-hidden rounded-lg">
                    <div
                        class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
                    >
                        <div class="flex justify-between">
                            <h2 class="text-h2">Edit the template</h2>
                            <Link
                                :href="route('template.index')"
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
                                    <label
                                        class="block mb-2 text-xs font-bold text-gray-600 uppercase mt-4"
                                    >
                                        Subjects:
                                    </label>
                                    <div>
                                        <div
                                            v-for="subject in subjects"
                                            :key="subject.id"
                                        >
                                            <label
                                                >{{ subject.name }}
                                                <input
                                                    type="checkbox"
                                                    :value="subject.id"
                                                    v-model="form.subjects"
                                                    @change="handleChange"
                                                />
                                            </label>
                                        </div>
                                    </div>
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
