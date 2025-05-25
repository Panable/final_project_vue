<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <!-- API Status Alert -->
        <div v-if="apiStatus" :class="['alert', apiStatus.status === 'healthy' ? 'alert-success' : 'alert-danger', 'mt-3']">
          <strong>API Status:</strong> {{ apiStatus.message }}
          <div v-if="apiStatus.error" class="mt-2">
            <strong>Error:</strong> {{ apiStatus.error }}
          </div>
        </div>

        <div class="card mt-5">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Register</h2>
            <form @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  v-model="form.username"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="form.email"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="form.password"
                  required
                >
              </div>
              <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select
                  class="form-select"
                  id="role"
                  v-model="form.role"
                  required
                >
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div v-if="message" :class="['alert', messageType, 'mt-3']">
              {{ message }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';

const API_URL = 'https://mercury.swin.edu.au/cos30043/s103866373/project/api.php';

const form = reactive({
  username: '',
  email: '',
  password: '',
  role: 'user'
});

const message = ref('');
const messageType = ref('');
const apiStatus = ref(null);

// Check API health
const checkApiHealth = async () => {
  try {
    const response = await fetch(`${API_URL}?action=health`);
    const data = await response.json();
    apiStatus.value = data;
    console.log('API Health Check:', data);
  } catch (error) {
    console.error('API Health Check Error:', error);
    apiStatus.value = {
      status: 'unhealthy',
      message: 'Failed to check API health',
      error: error.message
    };
  }
};

// Check API health when component mounts
onMounted(() => {
  checkApiHealth();
});

const handleSubmit = async () => {
  try {
    console.log('Sending registration request to:', API_URL);
    console.log('Request payload:', {
      action: 'register',
      ...form
    });
    
    const response = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        action: 'register',
        ...form
      })
    });

    console.log('Response status:', response.status);
    console.log('Response headers:', Object.fromEntries(response.headers.entries()));
    
    // Get the raw text first
    const rawText = await response.text();
    console.log('Raw response:', rawText);
    
    // Try to parse it as JSON
    let data;
    try {
      data = JSON.parse(rawText);
      console.log('Parsed response data:', data);
    } catch (parseError) {
      console.error('JSON Parse Error:', parseError);
      throw new Error(`Invalid JSON response: ${rawText}`);
    }
    
    if (response.ok) {
      message.value = data.message || 'Registration successful!';
      messageType.value = 'alert-success';
      // Clear form
      form.username = '';
      form.email = '';
      form.password = '';
      form.role = 'user';
    } else {
      message.value = data.error || 'Registration failed';
      messageType.value = 'alert-danger';
    }
  } catch (error) {
    console.error('Registration error:', error);
    console.error('Error details:', {
      message: error.message,
      stack: error.stack
    });
    message.value = `Error: ${error.message || 'An error occurred. Please try again.'}`;
    messageType.value = 'alert-danger';
  }
};
</script>

<style scoped>
.card {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.alert {
  margin-bottom: 0;
}
</style> 