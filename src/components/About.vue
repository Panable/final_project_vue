<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- About Section -->
        <div class="bg-light rounded-3 p-4 mb-5">
          <h2 class="display-4 mb-4">About TechVision</h2>
          <p class="lead mb-4">
            TechVision is your premier destination for staying informed about the latest technological advancements, 
            industry trends, and digital innovations. Our platform brings together tech enthusiasts, professionals, 
            and curious minds in a vibrant community focused on the future of technology.
          </p>
          <div class="row g-4 mb-4">
            <div class="col-md-4">
              <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                  <i class="bi bi-lightning-charge display-4 text-primary"></i>
                  <h4 class="mt-3">Fast Updates</h4>
                  <p class="mb-0">Real-time news and updates from the tech world</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                  <i class="bi bi-people display-4 text-primary"></i>
                  <h4 class="mt-3">Community</h4>
                  <p class="mb-0">Join discussions with tech enthusiasts worldwide</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center p-4">
                  <i class="bi bi-graph-up display-4 text-primary"></i>
                  <h4 class="mt-3">Insights</h4>
                  <p class="mb-0">Expert analysis and market trends</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Section -->
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
                <div class="d-flex gap-3">
                  <div class="form-check">
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
                  <div class="form-check">
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

              <div v-if="showWelcome" class="alert alert-success mt-4 animate__animated animate__fadeIn">
                <h4 class="alert-heading">Welcome, {{ firstName }} {{ lastName }}!</h4>
                <p class="mb-0">Thank you for joining our tech community. We're excited to have you here!</p>
              </div>
            </form>

            <div v-if="selectedImage" class="position-relative rounded-3 overflow-hidden">
              <img 
                :src="imageSrc" 
                :alt="selectedImage" 
                class="img-fluid shadow-sm"
              >
              <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4">
                <h4 class="mb-1">{{ selectedImage === 'mountain' ? 'Mountain View' : 'Ocean View' }}</h4>
                <p class="mb-0">{{ selectedImage === 'mountain' ? 'Explore the heights of technology' : 'Dive into the depths of innovation' }}</p>
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
.hover-shadow:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
