<template>
  <div class="container py-4">
    <h1 class="text-center mb-4">Kanban Board</h1>
    
    <!-- Add Task Form -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <input 
          v-model="newTask.title" 
          type="text" 
          class="form-control" 
          placeholder="Task Title"
        >
      </div>
      <div class="col-md-4">
        <select v-model="newTask.tag" class="form-select">
          <option value="">Select Tag</option>
          <option value="bug">Bug</option>
          <option value="feature">Feature</option>
          <option value="enhancement">Enhancement</option>
        </select>
      </div>
      <div class="col-md-4">
        <button @click="addTask" class="btn btn-primary w-100">Add Task</button>
      </div>
    </div>

    <!-- Kanban Columns -->
    <div class="row g-4">
      <KanbanColumn
        title="Backlog"
        v-model:tasks="backlog"
        @change="onChange"
      />
      
      <KanbanColumn
        title="In Progress"
        v-model:tasks="inProgress"
        @change="onChange"
      />
      
      <KanbanColumn
        title="Done"
        v-model:tasks="done"
        @change="onChange"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import KanbanColumn from './KanbanColumn.vue'

// Task state
const backlog = ref([])
const inProgress = ref([])
const done = ref([])

// New task form
const newTask = ref({
  title: '',
  tag: ''
})

// Add new task
const addTask = () => {
  if (!newTask.value.title || !newTask.value.tag) return

  backlog.value.push({
    id: Date.now(),
    title: newTask.value.title,
    tag: newTask.value.tag
  })

  // Reset form
  newTask.value.title = ''
  newTask.value.tag = ''
}

// Handle drag and drop changes
const onChange = (event) => {
  console.log('Task moved:', event)
}

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