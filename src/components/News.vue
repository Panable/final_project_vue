<template>
  <div class="news-container">
    <!-- Search Section -->
    <div class="search-section mb-4">
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
      <div v-if="filteredNews.length === 0" class="col-12 text-center py-5">
        <div class="no-results">
          <i class="bi bi-newspaper display-1 text-muted"></i>
          <h3 class="mt-3">No news found</h3>
          <p class="text-muted">Try adjusting your search criteria</p>
        </div>
      </div>
      <div v-else class="col-md-6 col-lg-4" v-for="item in paginatedNews" :key="item.id">
        <div class="news-card h-100">
          <div class="news-card-image">
            <img :src="item.image" :alt="item.title" class="img-fluid">
            <span class="category-badge">{{ item.category }}</span>
          </div>
          <div class="news-card-content p-3">
            <div class="news-date text-muted mb-2">
              <i class="bi bi-calendar3"></i> {{ formatDate(item.date) }}
            </div>
            <h3 class="news-title h5 mb-3">{{ item.title }}</h3>
            <p class="news-excerpt">{{ item.content }}</p>
            <button class="btn btn-link p-0">Read more <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-section mt-4">
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
  news.value = await response.json()
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
.news-container {
  padding: 2rem 0;
}

.search-section {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.news-card {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
  overflow: hidden;
}

.news-card:hover {
  transform: translateY(-5px);
}

.news-card-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.news-card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.category-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.875rem;
}

.news-title {
  color: #2c3e50;
  font-weight: 600;
}

.news-excerpt {
  color: #6c757d;
  font-size: 0.9rem;
  line-height: 1.5;
}

.no-results {
  padding: 3rem;
  background: #f8f9fa;
  border-radius: 0.5rem;
}

@media (max-width: 768px) {
  .search-section {
    padding: 1rem;
  }
  
  .news-card {
    margin-bottom: 1rem;
  }
}
</style>
