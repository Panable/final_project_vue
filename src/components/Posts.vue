<template>
  <div class="container py-5">
    <!-- Admin Controls -->
    <div v-if="isAdmin" class="mb-4">
      <button class="btn btn-primary" @click="showCreateModal = true">
        <i class="bi bi-plus-lg"></i> New Post
      </button>
    </div>

    <!-- Posts Grid -->
    <div class="row g-4">
      <div v-if="posts.length === 0" class="col-12">
        <div class="text-center py-5 bg-light rounded-3">
          <i class="bi bi-newspaper display-1 text-muted"></i>
          <h3 class="mt-3">No posts yet</h3>
          <p class="text-muted" v-if="isAdmin">Create your first post!</p>
        </div>
      </div>
      <div v-else class="col-md-6 col-lg-4" v-for="post in paginatedPosts" :key="post.id">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <h3 class="card-title h5 mb-0">{{ post.title }}</h3>
              <div v-if="isAdmin" class="d-flex">
                <button class="btn btn-sm btn-outline-primary me-2" @click="editPost(post)">Edit</button>
                <button class="btn btn-sm btn-outline-danger" @click="deletePost(post.id)">Delete</button>
              </div>
            </div>
            <p class="card-text">{{ post.content }}</p>
            <div class="d-flex justify-content-between align-items-center">
              <button 
                class="btn btn-link p-0" 
                @click="toggleLike(post)"
                :class="{ 'text-danger': post.user_liked }"
              >
                <i class="bi" :class="post.user_liked ? 'bi-heart-fill' : 'bi-heart'"></i>
                {{ post.like_count }} likes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      <Pagination 
        :total="posts.length" 
        :page-size="itemsPerPage" 
        @page-changed="changePage"
      />
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="postModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ editingPost ? 'Edit Post' : 'New Post' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="savePost">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" v-model="postForm.title" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea class="form-control" v-model="postForm.content" rows="4" required></textarea>
              </div>
              <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import Pagination from './Pagination.vue'
import { getUrl } from '../utils/url'
import { Modal, Dropdown } from 'bootstrap'

const API_URL = getUrl('api.php')
const posts = ref([])
const currentPage = ref(1)
const itemsPerPage = 6
const isAdmin = ref(false)
const showCreateModal = ref(false)
const editingPost = ref(null)
const postForm = ref({ title: '', content: '' })
let postModal = null

onMounted(async () => {
  // Initialize Bootstrap modal
  const modalElement = document.getElementById('postModal')
  if (modalElement) {
    postModal = new Modal(modalElement)
  }
  
  // Check if user is admin
  const response = await fetch(`${API_URL}?action=current_user`, {
    credentials: 'include'
  })
  const data = await response.json()
  isAdmin.value = data.user?.role === 'admin'
  
  // Load posts
  loadPosts()
})

const loadPosts = async () => {
  try {
    const response = await fetch(`${API_URL}?action=posts`, {
      credentials: 'include'
    })
    const data = await response.json()
    if (data.success) {
      posts.value = data.posts
      await nextTick()
      document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        new Dropdown(el)
      })
    }
  } catch (error) {
    console.error('Error loading posts:', error)
  }
}

const paginatedPosts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return posts.value.slice(start, start + itemsPerPage)
})

const changePage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const toggleLike = async (post) => {
  try {
    const response = await fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        action: 'toggle_like',
        post_id: post.id
      })
    })
    const data = await response.json()
    if (data.success) {
      // Update the post's like status and count
      post.user_liked = data.action === 'liked'
      post.like_count += data.action === 'liked' ? 1 : -1
    }
  } catch (error) {
    console.error('Error toggling like:', error)
  }
}

const editPost = (post) => {
  editingPost.value = post
  postForm.value = { ...post }
  postModal.show()
}

const deletePost = async (id) => {
  if (!confirm('Are you sure you want to delete this post?')) return
  
  try {
    const response = await fetch(API_URL, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        action: 'delete_post',
        id
      })
    })
    const data = await response.json()
    if (data.success) {
      posts.value = posts.value.filter(p => p.id !== id)
      await nextTick()
      document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        new Dropdown(el)
      })
    }
  } catch (error) {
    console.error('Error deleting post:', error)
  }
}

const savePost = async () => {
  try {
    const method = editingPost.value ? 'PUT' : 'POST'
    const action = editingPost.value ? 'update_post' : 'create_post'
    const body = {
      action,
      ...postForm.value
    }
    
    if (editingPost.value) {
      body.id = editingPost.value.id
    }
    
    const response = await fetch(API_URL, {
      method,
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(body)
    })
    
    const data = await response.json()
    if (data.success) {
      if (postModal) {
        postModal.hide()
      }
      await loadPosts() // Reload posts to get fresh data
      postForm.value = { title: '', content: '' }
      editingPost.value = null
      showCreateModal.value = false
      await nextTick()
      document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        new Dropdown(el)
      })
    }
  } catch (error) {
    console.error('Error saving post:', error)
  }
}

// Watch for modal show/hide
watch(showCreateModal, (show) => {
  if (show && postModal) {
    editingPost.value = null
    postForm.value = { title: '', content: '' }
    postModal.show()
  }
})
</script> 