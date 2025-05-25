<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <router-link class="navbar-brand" to="/">My App</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/news">News</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/about">About</router-link>
          </li>
          <li class="nav-item" v-if="isLoggedIn">
            <router-link class="nav-link" to="/posts">Posts</router-link>
          </li>
        </ul>
        <ul class="navbar-nav">
          <template v-if="isLoggedIn">
            <li class="nav-item">
              <span class="nav-link">{{ username }}</span>
            </li>
            <li class="nav-item">
              <button class="btn btn-link nav-link" @click="logout">Logout</button>
            </li>
          </template>
          <template v-else>
            <li class="nav-item">
              <router-link class="nav-link" to="/login">Login</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/register">Register</router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { getUrl } from '../utils/url'

const router = useRouter()
const route = useRoute()
const isLoggedIn = ref(false)
const username = ref('')

const checkAuthStatus = async () => {
  try {
    const response = await fetch(`${getUrl('api.php')}?action=current_user`)
    const data = await response.json()
    if (data.user) {
      isLoggedIn.value = true
      username.value = data.user.username
    } else {
      isLoggedIn.value = false
      username.value = ''
    }
  } catch (error) {
    console.error('Error checking auth status:', error)
    isLoggedIn.value = false
    username.value = ''
  }
}

// Check auth status on mount
onMounted(checkAuthStatus)

// Watch for route changes to update auth status
watch(() => route.path, checkAuthStatus)

const logout = async () => {
  try {
    console.log('Attempting logout...')
    const response = await fetch(getUrl('api.php'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        action: 'logout'
      })
    })
    
    const data = await response.json()
    console.log('Logout response:', data)
    
    if (data.success) {
      isLoggedIn.value = false
      username.value = ''
      router.push('/login')
    }
  } catch (error) {
    console.error('Logout error:', error)
  }
}
</script> 