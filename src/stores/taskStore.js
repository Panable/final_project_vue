import { ref, watch } from 'vue'

// Get initial state from localStorage or use empty arrays
const getStoredTasks = () => {
  const stored = localStorage.getItem('kanban-tasks')
  if (stored) {
    return JSON.parse(stored)
  }
  return {
    backlog: [],
    inProgress: [],
    done: []
  }
}

// Create reactive state
const backlog = ref(getStoredTasks().backlog)
const inProgress = ref(getStoredTasks().inProgress)
const done = ref(getStoredTasks().done)

// Watch for changes and save to localStorage
watch([backlog, inProgress, done], ([newBacklog, newInProgress, newDone]) => {
  localStorage.setItem('kanban-tasks', JSON.stringify({
    backlog: newBacklog,
    inProgress: newInProgress,
    done: newDone
  }))
}, { deep: true })

export function useTaskStore() {
  return {
    backlog,
    inProgress,
    done
  }
} 