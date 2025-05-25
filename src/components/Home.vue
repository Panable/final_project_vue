<template>
  <div class="home-container">
    <!-- Hero Section -->
    <div class="hero-section text-center py-5 mb-5">
      <h1 class="display-4 fw-bold mb-4">Welcome to TechVision</h1>
      <p class="lead mb-4">Your gateway to the latest in technology and innovation</p>
      <div class="hero-buttons">
        <button class="btn btn-primary btn-lg me-3">Get Started</button>
        <button class="btn btn-outline-primary btn-lg">Learn More</button>
      </div>
    </div>

    <!-- Features Section -->
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <i class="bi bi-laptop display-4 mb-3 text-primary"></i>
            <h3>Latest Tech</h3>
            <p>Stay updated with the newest technological advancements and innovations.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <i class="bi bi-people display-4 mb-3 text-primary"></i>
            <h3>Community</h3>
            <p>Join our growing community of tech enthusiasts and professionals.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <i class="bi bi-graph-up display-4 mb-3 text-primary"></i>
            <h3>Insights</h3>
            <p>Get valuable insights and analysis on emerging tech trends.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Gallery -->
    <div class="container mt-5">
      <div class="row g-4">
        <div class="col-md-6">
          <div class="image-card">
            <img 
              src="https://images.unsplash.com/photo-1518770660439-4636190af475"
              alt="Modern Technology" 
              class="img-fluid rounded shadow"
            >
            <div class="image-overlay">
              <h4>Innovation</h4>
              <p>Discover the future of technology</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="image-card">
            <img 
              src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b"
              alt="Digital World" 
              class="img-fluid rounded shadow"
            >
            <div class="image-overlay">
              <h4>Digital World</h4>
              <p>Explore the digital landscape</p>
            </div>
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
.home-container {
  padding: 2rem 0;
}

.hero-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 1rem;
  margin: 0 1rem;
}

.feature-card {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-5px);
}

.image-card {
  position: relative;
  overflow: hidden;
  border-radius: 1rem;
}

.image-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
  color: white;
  padding: 1.5rem;
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.image-card:hover .image-overlay {
  transform: translateY(0);
}

@media (max-width: 768px) {
  .hero-section {
    margin: 0;
    border-radius: 0;
  }
  
  .feature-card {
    margin-bottom: 1rem;
  }
}
</style>
