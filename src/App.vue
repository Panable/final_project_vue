<template>
  <div class="app-container">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container">
        <router-link class="navbar-brand" to="/">
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
                class="nav-link" 
                :class="{ active: $route.path === '/' }" 
                to="/"
              >
                <i class="bi bi-house"></i> Home
              </router-link>
            </li>
            <li class="nav-item">
              <router-link 
                class="nav-link" 
                :class="{ active: $route.path === '/news' }" 
                to="/news"
              >
                <i class="bi bi-newspaper"></i> News
              </router-link>
            </li>
            <li class="nav-item">
              <router-link 
                class="nav-link" 
                :class="{ active: $route.path === '/about' }" 
                to="/about"
              >
                <i class="bi bi-info-circle"></i> About
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
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
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding: 2rem 0;
}

.navbar {
  padding: 1rem 0;
}

.navbar-brand {
  font-weight: 600;
  font-size: 1.5rem;
}

.nav-link {
  font-weight: 500;
  padding: 0.5rem 1rem !important;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: #0d6efd !important;
}

.nav-link.active {
  color: #0d6efd !important;
}

.footer {
  border-top: 1px solid #dee2e6;
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

/* Responsive Adjustments */
@media (max-width: 768px) {
  .main-content {
    padding: 1rem 0;
  }
  
  .navbar-brand {
    font-size: 1.25rem;
  }
}
</style>
