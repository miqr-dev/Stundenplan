<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import SettingsSubMenu from "@/Components/SettingsSubMenu.vue";
import { VSwatches } from "vue3-swatches";
import "vue3-swatches/dist/style.css";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, onMounted } from "vue";

const props = defineProps({
  teacher: Object,
  subjects: Object,
  cities: Object,
});

const showSubjects = ref(true);

const form = useForm({
  id: props.teacher.id,
  name: props.teacher.name,
  surname: props.teacher.surname,
  email: props.teacher.email,
  color: props.teacher.color,
  maxhourweek: props.teacher.maxhourweek,
  subjects: props.teacher.subjects,
  cities: props.teacher.cities,
  teacherNotAvailable: props.teacher.teacherNotAvailable,
});

const update = () => {
  form.patch(route("teacher.update", form.id));
};

const destroy = () => {
    form.delete(route("teacher.destroy", form.id));
};
</script>

<template>
  <Head title="Teachers" />
  <BreezeAuthenticatedLayout>
    <header class="bg-gray-100 shadow">
      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <SettingsSubMenu></SettingsSubMenu>
      </div>
    </header>
    <div class="py-12">
      <div class="max-w-14xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-100 overflow-hidden sm:rounded-lg">
          <div
            class="p-6 bg-gray-100 text-h2 font-bold text-p-gray h-full w-1/2 shadow-md mx-auto"
          >
            <div class="flex justify-between">
              <h2 class="text-h2">Edit {{ form.name }}, {{ form.surname }}</h2>
              <Link :href="route('teacher.index')" class="text-blue-500 text-p"
                >X</Link
              >
            </div>
            <div
              class="bg-gray-100 p-5 rounded-xl mx-auto text-p font-bold space-y-2 mt-5 shadow-sm sm:rounded-lg"
            >
              <form @submit.prevent="update">
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.name"
                    label="Name"
                    type="text"
                    :error="form.errors.name"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.surname"
                    label="surname"
                    type="text"
                    :error="form.errors.surname"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.maxhourweek"
                    label="Max Hour / Week"
                    type="text"
                    :error="form.errors.maxhourweek"
                  />
                </div>
                <div class="mb-6">
                  <SimpleInput
                    v-model="form.email"
                    label="email"
                    type="text"
                    :error="form.errors.email"
                  />
                </div>
                <!-- implement the vue-swatch here -->
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="subject"
                    @click="showSubjects = !showSubjects"
                  >
                    Subjects
                  </label>
                  <div v-show="showSubjects">
                    <div v-for="subject in subjects" :key="subject.id">
                      <label
                        >{{ subject.name }}
                        <input
                          type="checkbox"
                          :value="subject.id"
                          v-model="form.subjects"
                        />
                      </label>
                    </div>
                  </div>

                  <div
                    v-if="form.errors.subjects"
                    v-text="form.errors.subjects"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-6">
                  <label
                    class="block mb-2 text-xs font-bold text-gray-600 uppercase"
                    for="cities"
                  >
                    Cities
                  </label>
                  <div v-for="city in cities" :key="city.id">
                    <label
                      >{{ city.name }}
                      <input
                        type="checkbox"
                        :value="city.id"
                        v-model="form.cities"
                      />
                    </label>
                  </div>
                  <div
                    v-if="form.errors.cities"
                    v-text="form.errors.cities"
                    class="text-red-500 text-sm mt-1"
                  ></div>
                </div>
                <div class="mb-1 flex justify-between">
                  <SimpleDelete @click="destroy" buttonText="Delete Template" />
                  <SimpleSubmit :processing="form.processing" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
