<template>
  <div class="container mt-4">
    <h2 class="mb-4">Kanban Board</h2>
    
    <!-- Add New Card Form -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Add New Card</h5>
        <form @submit.prevent="createCard">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" v-model="newCard.title" required>
          </div>
          <div class="mb-3">
            <label for="tag" class="form-label">Tag</label>
            <select class="form-select" id="tag" v-model="newCard.tag">
              <option value="">None</option>
              <option value="bug">Bug</option>
              <option value="feature">Feature</option>
              <option value="enhancement">Enhancement</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Card</button>
        </form>
      </div>
    </div>

    <!-- Kanban Board -->
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            To Do
          </div>
          <div class="card-body">
            <draggable 
              v-model="todoCards" 
              group="cards"
              @change="onChange"
              item-key="id"
              class="min-height"
            >
              <template #item="{ element }">
                <div class="card mb-2">
                  <div class="card-body">
                    <h5 class="card-title">{{ element.title }}</h5>
                    <span v-if="element.tag" :class="getTagClass(element.tag)" class="badge mb-2">
                      {{ element.tag }}
                    </span>
                    <div class="d-flex justify-content-end">
                      <button @click="editCard(element)" class="btn btn-sm btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button @click="deleteCard(element.id)" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </draggable>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-primary text-white">
            In Progress
          </div>
          <div class="card-body">
            <draggable 
              v-model="inProgressCards" 
              group="cards"
              @change="onChange"
              item-key="id"
              class="min-height"
            >
              <template #item="{ element }">
                <div class="card mb-2">
                  <div class="card-body">
                    <h5 class="card-title">{{ element.title }}</h5>
                    <span v-if="element.tag" :class="getTagClass(element.tag)" class="badge mb-2">
                      {{ element.tag }}
                    </span>
                    <div class="d-flex justify-content-end">
                      <button @click="editCard(element)" class="btn btn-sm btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button @click="deleteCard(element.id)" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </draggable>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-success text-white">
            Done
          </div>
          <div class="card-body">
            <draggable 
              v-model="doneCards" 
              group="cards"
              @change="onChange"
              item-key="id"
              class="min-height"
            >
              <template #item="{ element }">
                <div class="card mb-2">
                  <div class="card-body">
                    <h5 class="card-title">{{ element.title }}</h5>
                    <span v-if="element.tag" :class="getTagClass(element.tag)" class="badge mb-2">
                      {{ element.tag }}
                    </span>
                    <div class="d-flex justify-content-end">
                      <button @click="editCard(element)" class="btn btn-sm btn-outline-primary me-2">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button @click="deleteCard(element.id)" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </draggable>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Card Modal -->
    <div class="modal fade" id="editCardModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Card</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateCard">
              <div class="mb-3">
                <label for="editTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="editTitle" v-model="editingCard.title" required>
              </div>
              <div class="mb-3">
                <label for="editTag" class="form-label">Tag</label>
                <select class="form-select" id="editTag" v-model="editingCard.tag">
                  <option value="">None</option>
                  <option value="bug">Bug</option>
                  <option value="feature">Feature</option>
                  <option value="enhancement">Enhancement</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import draggable from 'vuedraggable'
import { Modal } from 'bootstrap'
import { getUrl } from '../utils/url'

export default {
  name: 'Kanban',
  components: {
    draggable
  },
  setup() {
    const todoCards = ref([])
    const inProgressCards = ref([])
    const doneCards = ref([])
    const newCard = ref({
      title: '',
      tag: '',
      column: 'todo'
    })
    const editingCard = ref({
      id: null,
      title: '',
      tag: '',
      column: ''
    })
    let editModal = null

    const fetchCards = async () => {
      try {
        const response = await fetch(`${getUrl('api.php')}?action=cards`, {
          credentials: 'include'
        })
        const data = await response.json()
        if (data.success) {
          // Sort cards into their respective columns
          todoCards.value = data.cards.filter(card => card.column === 'todo')
          inProgressCards.value = data.cards.filter(card => card.column === 'in_progress')
          doneCards.value = data.cards.filter(card => card.column === 'done')
        }
      } catch (error) {
        console.error('Error fetching cards:', error)
      }
    }

    const createCard = async () => {
      try {
        const response = await fetch(getUrl('api.php'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({
            action: 'create_card',
            ...newCard.value
          })
        })
        const data = await response.json()
        if (data.success) {
          todoCards.value.unshift(data.card)
          newCard.value = {
            title: '',
            tag: '',
            column: 'todo'
          }
        }
      } catch (error) {
        console.error('Error creating card:', error)
      }
    }

    const updateCard = async () => {
      try {
        const response = await fetch(getUrl('api.php'), {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({
            action: 'update_card',
            ...editingCard.value
          })
        })
        const data = await response.json()
        if (data.success) {
          await fetchCards() // Refresh all cards to ensure correct state
          editModal.hide()
        }
      } catch (error) {
        console.error('Error updating card:', error)
      }
    }

    const deleteCard = async (id) => {
      if (!confirm('Are you sure you want to delete this card?')) return

      try {
        const response = await fetch(`${getUrl('api.php')}?action=delete_card&id=${id}`, {
          method: 'DELETE',
          credentials: 'include'
        })
        const data = await response.json()
        if (data.success) {
          await fetchCards() // Refresh all cards to ensure correct state
        }
      } catch (error) {
        console.error('Error deleting card:', error)
      }
    }

    const onChange = async (event) => {
      if (!event.added && !event.moved) return

      const card = event.added ? event.added.element : event.moved.element
      const newColumn = event.added ? event.added.to.id : event.moved.to.id

      try {
        const response = await fetch(getUrl('api.php'), {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({
            action: 'update_card',
            id: card.id,
            title: card.title,
            tag: card.tag,
            column: newColumn
          })
        })
        const data = await response.json()
        if (!data.success) {
          await fetchCards() // Refresh all cards if update failed
        }
      } catch (error) {
        console.error('Error updating card column:', error)
        await fetchCards() // Refresh all cards if there was an error
      }
    }

    const editCard = (card) => {
      editingCard.value = { ...card }
      editModal.show()
    }

    const getTagClass = (tag) => {
      const classes = {
        bug: 'bg-danger',
        feature: 'bg-primary',
        enhancement: 'bg-success'
      }
      return classes[tag] || 'bg-secondary'
    }

    onMounted(() => {
      fetchCards()
      editModal = new Modal(document.getElementById('editCardModal'))
    })

    return {
      todoCards,
      inProgressCards,
      doneCards,
      newCard,
      editingCard,
      createCard,
      updateCard,
      deleteCard,
      onChange,
      editCard,
      getTagClass
    }
  }
}
</script>

<style scoped>
.min-height {
  min-height: 200px;
}
</style> 