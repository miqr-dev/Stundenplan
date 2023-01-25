<script setup>
 import BreezeNavLink from '@/Components/NavLink.vue';
</script>

<template>
  <nav class="bg-gray-100 border-b border-gray-100">
    <!-- Secondary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-around h-8">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <BreezeNavLink :href="route('subject.index')" :active="route().current('subject.index')">
                        Subjects
                    </BreezeNavLink>
                    <BreezeNavLink :href="route('city.index')" :active="route().current('city.index')">
                        Cities
                    </BreezeNavLink>
                    <BreezeNavLink :href="route('location.index')" :active="route().current('location.index')">
                        Locations
                    </BreezeNavLink>
                    <BreezeNavLink :href="route('template.index')" :active="route().current('template.index')">
                        Template
                    </BreezeNavLink>
                </div>
            </div>
        </div>
    </div>
</nav>
</template>