<template>
  <div class="about-container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- About Section -->
        <div class="about-section mb-5">
          <h2 class="display-4 mb-4">About TechVision</h2>
          <p class="lead mb-4">
            TechVision is your premier destination for staying informed about the latest technological advancements, 
            industry trends, and digital innovations. Our platform brings together tech enthusiasts, professionals, 
            and curious minds in a vibrant community focused on the future of technology.
          </p>
          <div class="row g-4 mb-4">
            <div class="col-md-4">
              <div class="feature-item text-center">
                <i class="bi bi-lightning-charge display-4 text-primary"></i>
                <h4 class="mt-3">Fast Updates</h4>
                <p>Real-time news and updates from the tech world</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-item text-center">
                <i class="bi bi-people display-4 text-primary"></i>
                <h4 class="mt-3">Community</h4>
                <p>Join discussions with tech enthusiasts worldwide</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="feature-item text-center">
                <i class="bi bi-graph-up display-4 text-primary"></i>
                <h4 class="mt-3">Insights</h4>
                <p>Expert analysis and market trends</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Section -->
        <div class="interactive-section">
          <div class="card shadow-sm">
            <div class="card-body p-4">
              <h3 class="card-title mb-4">Personalize Your Experience</h3>
              
              <form @submit.prevent="handleSubmit" class="mb-4">
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input 
                        type="text" 
                        class="form-control" 
                        id="firstName" 
                        v-model="firstName"
                        placeholder="First Name"
                        required
                      >
                      <label for="firstName">First Name</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating">
                      <input 
                        type="text" 
                        class="form-control" 
                        id="lastName" 
                        v-model="lastName"
                        placeholder="Last Name"
                        required
                      >
                      <label for="lastName">Last Name</label>
                    </div>
                  </div>
                </div>

                <div class="mt-4">
                  <label class="form-label">Choose Your Theme</label>
                  <div class="theme-options">
                    <div class="form-check form-check-inline">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        id="mountain" 
                        value="mountain" 
                        v-model="selectedImage"
                      >
                      <label class="form-check-label" for="mountain">
                        <i class="bi bi-mountain"></i> Mountain
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input 
                        class="form-check-input" 
                        type="radio" 
                        id="ocean" 
                        value="ocean" 
                        v-model="selectedImage"
                      >
                      <label class="form-check-label" for="ocean">
                        <i class="bi bi-water"></i> Ocean
                      </label>
                    </div>
                  </div>
                </div>

                <div v-if="showWelcome" class="welcome-message mt-4">
                  <div class="alert alert-success">
                    <h4 class="alert-heading">Welcome, {{ firstName }} {{ lastName }}!</h4>
                    <p class="mb-0">Thank you for joining our tech community. We're excited to have you here!</p>
                  </div>
                </div>
              </form>

              <div v-if="selectedImage" class="selected-image-container">
                <img 
                  :src="imageSrc" 
                  :alt="selectedImage" 
                  class="img-fluid rounded shadow-sm"
                >
                <div class="image-overlay">
                  <h4>{{ selectedImage === 'mountain' ? 'Mountain View' : 'Ocean View' }}</h4>
                  <p>{{ selectedImage === 'mountain' ? 'Explore the heights of technology' : 'Dive into the depths of innovation' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const firstName = ref('')
const lastName = ref('')
const selectedImage = ref('')
const showWelcome = ref(false)

const imageSrc = computed(() => {
  return selectedImage.value === 'mountain'
    ? 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b'
    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e'
})

const handleSubmit = () => {
  showWelcome.value = true
}
</script>

<style scoped>
.about-container {
  padding: 2rem 0;
}

.about-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 2rem;
  border-radius: 1rem;
}

.feature-item {
  padding: 1.5rem;
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-5px);
}

.interactive-section {
  margin-top: 2rem;
}

.theme-options {
  display: flex;
  gap: 1rem;
}

.form-check-inline {
  margin-right: 1rem;
}

.selected-image-container {
  position: relative;
  margin-top: 1rem;
  border-radius: 0.5rem;
  overflow: hidden;
}

.image-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
  color: white;
  padding: 1.5rem;
}

.welcome-message {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .about-section {
    padding: 1.5rem;
  }
  
  .feature-item {
    margin-bottom: 1rem;
  }
}
</style>
