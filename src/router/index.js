import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import News from '../components/News.vue'
import About from '../components/About.vue'
import Apply from '../components/Apply.vue'
import Register from '../components/Register.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/news', component: News },
  { path: '/about', component: About },
  { path: '/apply', component: Apply },
  { path: '/register', component: Register }
]

// Determine base path dynamically
const base = import.meta.env.PROD ? '/cos30043/s103866373/project/' : '/';

const router = createRouter({
  history: createWebHistory(base), // 🔥 dynamic base
  routes
})

export default router
