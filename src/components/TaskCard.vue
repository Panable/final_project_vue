<template>
  <div class="card mb-2 shadow-sm" :class="'border-start border-4 border-' + getTagColor(task.tag)">
    <div class="card-body py-2">
      <h5 class="card-title mb-2">{{ task.title }}</h5>
      <span class="badge" :class="'bg-' + getTagColor(task.tag)">{{ task.tag }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  task: {
    type: Object,
    required: true
  }
})

// Get Bootstrap color class based on tag
const getTagColor = (tag) => {
  switch(tag) {
    case 'bug':
      return 'danger'
    case 'feature':
      return 'success'
    case 'enhancement':
      return 'info'
    default:
      return 'secondary'
  }
}
</script> 