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
      <div class="col-md-4">
        <div class="bg-light rounded-3 p-3 h-100">
          <h3 class="h5 mb-3">Backlog</h3>
          <draggable 
            v-model="backlog" 
            group="tasks"
            class="min-vh-100"
            item-key="id"
            @change="onChange"
          >
            <template #item="{ element }">
              <div class="card mb-2 shadow-sm" :class="'border-start border-4 border-' + getTagColor(element.tag)">
                <div class="card-body py-2">
                  <h5 class="card-title mb-2">{{ element.title }}</h5>
                  <span class="badge" :class="'bg-' + getTagColor(element.tag)">{{ element.tag }}</span>
                </div>
              </div>
            </template>
          </draggable>
        </div>
      </div>

      <div class="col-md-4">
        <div class="bg-light rounded-3 p-3 h-100">
          <h3 class="h5 mb-3">In Progress</h3>
          <draggable 
            v-model="inProgress" 
            group="tasks"
            class="min-vh-100"
            item-key="id"
            @change="onChange"
          >
            <template #item="{ element }">
              <div class="card mb-2 shadow-sm" :class="'border-start border-4 border-' + getTagColor(element.tag)">
                <div class="card-body py-2">
                  <h5 class="card-title mb-2">{{ element.title }}</h5>
                  <span class="badge" :class="'bg-' + getTagColor(element.tag)">{{ element.tag }}</span>
                </div>
              </div>
            </template>
          </draggable>
        </div>
      </div>

      <div class="col-md-4">
        <div class="bg-light rounded-3 p-3 h-100">
          <h3 class="h5 mb-3">Done</h3>
          <draggable 
            v-model="done" 
            group="tasks"
            class="min-vh-100"
            item-key="id"
            @change="onChange"
          >
            <template #item="{ element }">
              <div class="card mb-2 shadow-sm" :class="'border-start border-4 border-' + getTagColor(element.tag)">
                <div class="card-body py-2">
                  <h5 class="card-title mb-2">{{ element.title }}</h5>
                  <span class="badge" :class="'bg-' + getTagColor(element.tag)">{{ element.tag }}</span>
                </div>
              </div>
            </template>
          </draggable>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'

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