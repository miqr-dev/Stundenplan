<script setup>
import { ref, onMounted, computed, onBeforeUnmount } from "vue";
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import Alert from "@/Components/Alert.vue";
import axios from "axios";
import { BellIcon } from "@heroicons/vue/20/solid";

const showingNavigationDropdown = ref(false);
const showAlert = ref(true);
const notifications = ref([]);
const notificationDropdown = ref(null);

const fetchNotifications = async () => {
  const response = await axios.get("/notifications");
  notifications.value = response.data.notifications;
};

const showNotificationDropdown = ref(false);
const handleClickOutside = (event) => {
  if (
    notificationDropdown.value &&
    !notificationDropdown.value.contains(event.target)
  ) {
    showNotificationDropdown.value = false;
  }
};

const notificationsCount = computed(() => {
  return notifications.value.length;
});

onMounted(() => {
  fetchNotifications();
  document.addEventListener("mousedown", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("mousedown", handleClickOutside);
});

const markAsRead = async (notificationId) => {
  try {
    await axios.post(`/notifications/${notificationId}/mark-as-read`);
    notifications.value = notifications.value.filter(
      (notification) => notification.id !== notificationId
    );
  } catch (error) {
    console.error("Error marking notification as read", error);
  }
};
</script>

<template>
  <div>
    <div v-if="$page.props.flash.success" class="absolute top-12 right-10 z-10">
      <Alert
        :show="showAlert"
        :on-dismiss="() => (showAlert = false)"
        intent="success"
        :title="$page.props.flash.success"
      />
    </div>
    <div v-if="$page.props.flash.error" class="absolute top-12 right-10 z-10">
      <Alert
        :show="showAlert"
        :on-dismiss="() => (showAlert = false)"
        intent="danger"
        :title="$page.props.flash.error"
      />
    </div>
    <div v-if="$page.props.error" class="absolute top-12 right-10 z-10">
      <Alert
        :show="showAlert"
        :on-dismiss="() => (showAlert = false)"
        intent="danger"
        :title="$page.props.error"
      />
    </div>
    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <BreezeApplicationLogo class="block h-9 w-auto" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <BreezeNavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  Dashboard
                </BreezeNavLink>
                <BreezeNavLink
                  :href="route('stundenplan.index')"
                  :active="route().current('stundenplan.index')"
                >
                  Stundenplan
                </BreezeNavLink>
                <BreezeNavLink
                  :href="route('settings')"
                  :active="
                    route().current('settings') ||
                    route().current('city.*') ||
                    route().current('location.*') ||
                    route().current('subject.*') ||
                    route().current('template.*') ||
                    route().current('teacher.*') ||
                    route().current('course.*') ||
                    route().current('teacher-not-available.*')
                  "
                >
                  Einstellungen
                </BreezeNavLink>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <div class="ml-3 relative">
                <!-- Notification Icon -->
                <div class="mr-3 relative">
                  <button
                    @click="
                      showNotificationDropdown = !showNotificationDropdown
                    "
                    class="p-2 bg-white rounded-full"
                  >
                    <BellIcon class="h-5 w-5 text-gray-500" />
                    <span
                      class="absolute top-0 right-0 inline-block bg-yellow-500 text-white text-xs rounded-full h-4 w-4 text-center"
                    >
                      {{ notificationsCount }}
                    </span>
                  </button>
                  <!-- Dropdown for Notifications -->
                  <div
                    v-if="notifications.length > 0 && showNotificationDropdown"
                    ref="notificationDropdown"
                    class="absolute right-0 mt-2 w-192 rounded-md shadow-lg z-20"
                  >
                    <div class="rounded-md bg-white shadow-xs grid grid-cols-2">
                      <!-- Notifications column -->
                      <div class="py-1">
                        <div class="px-4 py-2 font-bold text-white bg-gray-400">
                          Geplante Lehrkraft entfernt.
                        </div>
                        <div class="relative">
                          <!-- This container will hold the border and the notifications -->
                          <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="relative"
                          >
                            <a
                              href="#"
                              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              <!-- Display your notification message here -->
                              <span v-html="notification.data.message"></span>
                            </a>
                            <button
                              @click.prevent="markAsRead(notification.id)"
                              class="absolute top-0 right-0 mt-2 mr-2 px-2 py-1 text-xs bg-blue-500 text-white rounded"
                            >
                              Mark as Read
                            </button>
                          </div>
                        </div>
                      </div>
                      <!-- Others column -->
                      <div class="py-1">
                        <div class="px-4 py-2 font-bold text-white bg-gray-400">
                          Doppeltzuweisung
                        </div>
                        <!-- Add other items here -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <BreezeDropdown align="left" width="48">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                      >
                        {{ $page.props.auth.user.name }}

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content align="left" width="48">
                    <BreezeDropdownLink
                      :href="route('logout')"
                      method="post"
                      as="button"
                    >
                      Log Out
                    </BreezeDropdownLink>
                  </template>
                </BreezeDropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <BreezeResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
            >
              Dashboard
            </BreezeResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
              <div class="font-medium text-base text-gray-800">
                {{ $page.props.auth.user.name }}
              </div>
              <div class="font-medium text-sm text-gray-500">
                {{ $page.props.auth.user.email }}
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <BreezeResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
              >
                Log Out
              </BreezeResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
