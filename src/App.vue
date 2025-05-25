<template>
  <div class="min-vh-100 d-flex flex-column">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm py-3">
      <div class="container">
        <router-link class="navbar-brand fw-semibold fs-4" to="/">
          <i class="bi bi-cpu"></i> TechVision
        </router-link>
        
        <button 
          class="navbar-toggler" 
          type="button" 
          data-bs-toggle="collapse" 
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link 
                class="nav-link fw-medium px-3" 
                :class="{ active: $route.path === '/' }" 
                to="/"
              >
                <i class="bi bi-house"></i> Home
              </router-link>
            </li>
            <li class="nav-item">
              <router-link 
                class="nav-link fw-medium px-3" 
                :class="{ active: $route.path === '/news' }" 
                to="/news"
              >
                <i class="bi bi-newspaper"></i> News
              </router-link>
            </li>
            <li class="nav-item">
              <router-link 
                class="nav-link fw-medium px-3" 
                :class="{ active: $route.path === '/about' }" 
                to="/about"
              >
                <i class="bi bi-info-circle"></i> About
              </router-link>
            </li>
            <li class="nav-item">
              <router-link 
                class="nav-link fw-medium px-3" 
                :class="{ active: $route.path === '/apply' }" 
                to="/apply"
              >
                <i class="bi bi-person-plus"></i> Apply
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

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
// Root App component, routing handled by <router-view>
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
