<template>
  <div class="col-md-4">
    <div class="bg-light rounded-3 p-3 h-100">
      <h3 class="h5 mb-3">{{ title }}</h3>
      <draggable 
        v-model="tasksList" 
        group="tasks"
        class="min-vh-100"
        item-key="id"
        @change="onChange"
      >
        <template #item="{ element }">
          <TaskCard :task="element" />
        </template>
      </draggable>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import draggable from 'vuedraggable'
import TaskCard from './TaskCard.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  tasks: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['update:tasks', 'change'])

// Computed property with getter and setter for two-way binding
const tasksList = computed({
  get: () => props.tasks,
  set: (value) => emit('update:tasks', value)
})

const onChange = (event) => {
  emit('change', event)
}
</script> 