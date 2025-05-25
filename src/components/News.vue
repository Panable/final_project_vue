<template>
  <div class="container py-5">
    <!-- Search Section -->
    <div class="bg-light rounded-3 p-4 mb-4 shadow-sm">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input 
              type="text" 
              class="form-control" 
              v-model="search" 
              placeholder="Search news..."
            >
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select" v-model="categoryFilter">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>
        <div class="col-md-3">
          <input 
            type="date" 
            class="form-control" 
            v-model="dateFilter"
          >
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-secondary w-100" @click="clearFilters">
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- News Grid -->
    <div class="row g-4">
      <div v-if="filteredNews.length === 0" class="col-12">
        <div class="text-center py-5 bg-light rounded-3">
          <i class="bi bi-newspaper display-1 text-muted"></i>
          <h3 class="mt-3">No news found</h3>
          <p class="text-muted">Try adjusting your search criteria</p>
        </div>
      </div>
      <div v-else class="col-md-6 col-lg-4" v-for="item in paginatedNews" :key="item.id">
        <div class="card h-100 shadow-sm hover-shadow">
          <div class="position-relative">
            <img :src="item.image" :alt="item.title" class="card-img-top" style="height: 200px; object-fit: cover;">
            <span class="position-absolute top-0 end-0 m-3 bg-dark bg-opacity-75 text-white px-3 py-1 rounded-pill small">
              {{ item.category }}
            </span>
          </div>
          <div class="card-body">
            <div class="text-muted mb-2">
              <i class="bi bi-calendar3"></i> {{ formatDate(item.date) }}
            </div>
            <h3 class="card-title h5 mb-3">{{ item.title }}</h3>
            <p class="card-text text-muted">{{ item.content }}</p>
            <button class="btn btn-link p-0">Read more <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      <Pagination 
        :total="filteredNews.length" 
        :page-size="itemsPerPage" 
        @page-changed="changePage"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Pagination from './Pagination.vue'

const news = ref([])
const search = ref('')
const categoryFilter = ref('')
const dateFilter = ref('')
const currentPage = ref(1)
const itemsPerPage = 6

onMounted(async () => {
  const response = await fetch('/news.json')
  const data = await response.json()
  news.value = data.news
})

const categories = computed(() => {
  const uniqueCategories = new Set(news.value.map(item => item.category))
  return Array.from(uniqueCategories)
})

const filteredNews = computed(() => {
  return news.value.filter(item => {
    const matchesSearch = 
      item.title.toLowerCase().includes(search.value.toLowerCase()) ||
      item.content.toLowerCase().includes(search.value.toLowerCase())
    
    const matchesCategory = !categoryFilter.value || item.category === categoryFilter.value
    
    const matchesDate = !dateFilter.value || item.date === dateFilter.value
    
    return matchesSearch && matchesCategory && matchesDate
  })
})

const paginatedNews = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredNews.value.slice(start, start + itemsPerPage)
})

const changePage = (page) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const clearFilters = () => {
  search.value = ''
  categoryFilter.value = ''
  dateFilter.value = ''
  currentPage.value = 1
}

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}
</script>

<style scoped>
.hover-shadow:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
