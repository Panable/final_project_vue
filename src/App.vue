<template>
  <div class="min-vh-100 d-flex flex-column">
    <!-- Navigation Bar -->
    <Navbar />

    <!-- Main Content -->
    <main class="flex-grow-1 py-5">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <footer class="footer py-3 bg-light border-top">
      <div class="container text-center">
        <p class="mb-0">
          &copy; 2024 TechVision. All rights reserved.
          <span class="ms-2">
            <a href="#" class="text-decoration-none me-3">
              <i class="bi bi-github"></i>
            </a>
            <a href="#" class="text-decoration-none me-3">
              <i class="bi bi-twitter"></i>
            </a>
            <a href="#" class="text-decoration-none">
              <i class="bi bi-linkedin"></i>
            </a>
          </span>
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'
</script>

<style>
/* Only keeping styles that can't be handled by Bootstrap */
.nav-link:hover {
  color: #0d6efd !important;
}

.nav-link.active {
  color: #0d6efd !important;
}

/* Page Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
