import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import News from '../components/News.vue'
import About from '../components/About.vue'
import KanbanBoard from '../components/KanbanBoard.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/news', component: News },
  { path: '/about', component: About },
  {
    path: '/kanban',
    name: 'kanban',
    component: KanbanBoard
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
