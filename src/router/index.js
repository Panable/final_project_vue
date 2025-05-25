import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import News from '../components/News.vue'
import Posts from '../components/Posts.vue'
import About from '../components/About.vue'
import { getUrl } from '../utils/url'

const router = createRouter({
  history: createWebHistory(import.meta.env.PROD ? '/cos30043/s103866373/project' : ''),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/news',
      name: 'news',
      component: News
    },
    {
      path: '/about',
      name: 'about',
      component: About
    },
    {
      path: '/posts',
      name: 'posts',
      component: Posts,
      meta: { requiresAuth: true }
    }
  ]
})

// Navigation guard for protected routes
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const response = await fetch(`${getUrl('api.php')}?action=current_user`)
      const data = await response.json()
      if (data.user) {
        next()
      } else {
        next('/login')
      }
    } catch (error) {
      console.error('Auth check failed:', error)
      next('/login')
    }
  } else {
    next()
  }
})

export default router
