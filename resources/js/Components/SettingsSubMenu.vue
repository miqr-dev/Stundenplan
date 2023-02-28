<script setup>
import BreezeNavLink from "@/Components/NavLink.vue";
</script>

<template>
  <nav class="bg-gray-100 border-b border-gray-100">
    <!-- Secondary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-around h-8">
        <div class="flex">
          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <BreezeNavLink
              :href="route('course.index')"
              :active="route().current('course.*')"
            >
              Courses
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('template.index')"
              :active="route().current('template.*')"
            >
              Template
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('subject.index')"
              :active="route().current('subject.*')"
            >
              Subjects
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('location.index')"
              :active="route().current('location.*')"
            >
              Locations
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('city.index')"
              :active="route().current('city.*')"
            >
              Cities
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('grid.index')"
              :active="route().current('grid.*')"
            >
              Grid
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('teacher.index')"
              :active="route().current('teacher.*')"
            >
              Teachers
            </BreezeNavLink>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
