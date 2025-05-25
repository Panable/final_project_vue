<template>
  <div class="container py-5">
    <!-- Hero Section -->
    <div class="bg-light rounded-3 p-5 mb-5 text-center">
      <h1 class="display-4 fw-bold mb-4">Welcome to TechVision</h1>
      <p class="lead mb-4">Your gateway to the latest in technology and innovation</p>
      <div class="d-flex justify-content-center gap-3">
        <button class="btn btn-primary btn-lg">Get Started</button>
        <button class="btn btn-outline-primary btn-lg">Learn More</button>
      </div>
    </div>

    <!-- Features Section -->
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow">
          <div class="card-body text-center p-4">
            <i class="bi bi-laptop display-4 mb-3 text-primary"></i>
            <h3>Latest Tech</h3>
            <p>Stay updated with the newest technological advancements and innovations.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow">
          <div class="card-body text-center p-4">
            <i class="bi bi-people display-4 mb-3 text-primary"></i>
            <h3>Community</h3>
            <p>Join our growing community of tech enthusiasts and professionals.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm hover-shadow">
          <div class="card-body text-center p-4">
            <i class="bi bi-graph-up display-4 mb-3 text-primary"></i>
            <h3>Insights</h3>
            <p>Get valuable insights and analysis on emerging tech trends.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Gallery -->
    <div class="row g-4 mt-5">
      <div class="col-md-6">
        <div class="position-relative overflow-hidden rounded-3 shadow-sm">
          <img 
            src="https://images.unsplash.com/photo-1518770660439-4636190af475"
            alt="Modern Technology" 
            class="img-fluid"
          >
          <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4 translate-y-100 transition-transform">
            <h4>Innovation</h4>
            <p class="mb-0">Discover the future of technology</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="position-relative overflow-hidden rounded-3 shadow-sm">
          <img 
            src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b"
            alt="Digital World" 
            class="img-fluid"
          >
          <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4 translate-y-100 transition-transform">
            <h4>Digital World</h4>
            <p class="mb-0">Explore the digital landscape</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Static homepage component
</script>

<style scoped>
.hover-shadow:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.transition-transform {
  transition: transform 0.3s ease;
}

.position-relative:hover .translate-y-100 {
  transform: translateY(0);
}
</style>
