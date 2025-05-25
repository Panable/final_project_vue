<template>
  <div class="col-md-4 mb-3">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">{{ news.title }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ news.category }} | {{ news.date }}</h6>
        <p class="card-text">{{ news.content }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  news: Object
})
</script>
