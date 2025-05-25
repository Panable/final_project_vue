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
              <span class="nav-link">
                {{ username }}
                <span v-if="isAdmin" class="badge bg-primary ms-1">Admin</span>
              </span>
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
const isAdmin = ref(false)

const checkAuthStatus = async () => {
  try {
    const response = await fetch(`${getUrl('api.php')}?action=current_user`, {
      credentials: 'include'
    })
    const data = await response.json()
    isLoggedIn.value = !!data.user
    username.value = data.user?.username || ''
    isAdmin.value = data.user?.role === 'admin'
  } catch (error) {
    console.error('Auth check failed:', error)
    isLoggedIn.value = false
    username.value = ''
    isAdmin.value = false
  }
}

// Check auth status on mount and route changes
onMounted(checkAuthStatus)
watch(() => route.path, checkAuthStatus)

const logout = async () => {
  try {
    const response = await fetch(getUrl('api.php'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ action: 'logout' })
    })
    const data = await response.json()
    if (data.success) {
      isLoggedIn.value = false
      username.value = ''
      isAdmin.value = false
      router.push('/login')
    }
  } catch (error) {
    console.error('Logout error:', error)
  }
}
</script> 