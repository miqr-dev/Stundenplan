<script setup>
import BreezeNavLink from "@/Components/NavLink.vue";
</script>

<template>
  <nav class="bg-gray-100 border-b border-gray-100">
    <!-- Sekundäres Navigationsmenü -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-around h-8">
        <div class="flex">
          <!-- Navigationslinks -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <BreezeNavLink
              :href="route('course.index')"
              :active="route().current('course.*') || route().current('ferien.*')"
            >
              Kurse
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('template.index')"
              :active="route().current('template.*')"
            >
              Vorlage
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('subject.index')"
              :active="route().current('subject.*')"
            >
              Fächer
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('location.index')"
              :active="route().current('location.*')"
            >
              Standorte
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('city.index')"
              :active="route().current('city.*')"
            >
              Städte
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('grid.index')"
              :active="route().current('grid.*')"
            >
              Stundenraster
            </BreezeNavLink>
            <BreezeNavLink
              :href="route('teacher.index')"
              :active="route().current('teacher.*')"
            >
              Dozenten / innen
            </BreezeNavLink>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>