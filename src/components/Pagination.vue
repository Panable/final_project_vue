<template>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <!-- Previous Button -->
      <li class="page-item" :class="{ disabled: currentPage === 1 }">
        <button 
          class="page-link" 
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          aria-label="Previous page"
        >
          <i class="bi bi-chevron-left"></i>
        </button>
      </li>

      <!-- First Page -->
      <li v-if="showFirstPage" class="page-item">
        <button class="page-link" @click="changePage(1)">1</button>
      </li>

      <!-- Left Ellipsis -->
      <li v-if="showLeftEllipsis" class="page-item disabled">
        <span class="page-link">...</span>
      </li>

      <!-- Page Numbers -->
      <li 
        v-for="page in displayedPages" 
        :key="page" 
        class="page-item"
        :class="{ active: currentPage === page }"
      >
        <button 
          class="page-link" 
          @click="changePage(page)"
          :aria-current="currentPage === page ? 'page' : undefined"
        >
          {{ page }}
        </button>
      </li>

      <!-- Right Ellipsis -->
      <li v-if="showRightEllipsis" class="page-item disabled">
        <span class="page-link">...</span>
      </li>

      <!-- Last Page -->
      <li v-if="showLastPage" class="page-item">
        <button class="page-link" @click="changePage(totalPages)">{{ totalPages }}</button>
      </li>

      <!-- Next Button -->
      <li class="page-item" :class="{ disabled: currentPage === totalPages }">
        <button 
          class="page-link" 
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          aria-label="Next page"
        >
          <i class="bi bi-chevron-right"></i>
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  total: {
    type: Number,
    required: true
  },
  pageSize: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['page-changed'])
const currentPage = ref(1)

const totalPages = computed(() => Math.ceil(props.total / props.pageSize))

// Calculate which pages to show
const displayedPages = computed(() => {
  const pages = []
  const maxVisiblePages = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
  let end = Math.min(totalPages.value, start + maxVisiblePages - 1)

  if (end - start + 1 < maxVisiblePages) {
    start = Math.max(1, end - maxVisiblePages + 1)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  return pages
})

// Show/hide first page
const showFirstPage = computed(() => {
  return displayedPages.value[0] > 1
})

// Show/hide last page
const showLastPage = computed(() => {
  return displayedPages.value[displayedPages.value.length - 1] < totalPages.value
})

// Show/hide ellipsis
const showLeftEllipsis = computed(() => {
  return displayedPages.value[0] > 2
})

const showRightEllipsis = computed(() => {
  return displayedPages.value[displayedPages.value.length - 1] < totalPages.value - 1
})

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    emit('page-changed', page)
  }
}

watch(currentPage, (newPage) => {
  emit('page-changed', newPage)
})
</script>

<style scoped>
.pagination {
  margin: 0;
}

.page-link {
  color: #2c3e50;
  border: none;
  padding: 0.5rem 1rem;
  margin: 0 0.2rem;
  border-radius: 0.25rem;
  transition: all 0.3s ease;
}

.page-link:hover {
  background-color: #e9ecef;
  color: #2c3e50;
}

.page-item.active .page-link {
  background-color: #0d6efd;
  color: white;
}

.page-item.disabled .page-link {
  color: #6c757d;
  pointer-events: none;
  background-color: #f8f9fa;
}

@media (max-width: 576px) {
  .page-link {
    padding: 0.4rem 0.8rem;
    margin: 0 0.1rem;
  }
}
</style>
