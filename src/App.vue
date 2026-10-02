<template>
  <header>
    <nav>
      <RouterLink to="/">Accueil</RouterLink>

      <RouterLink to="/products">
        Produits
      </RouterLink>

      <RouterLink to="/cart">
        Panier ({{ cartStore.totalItems }})
      </RouterLink>

      <template v-if="!authStore.isAuthenticated">
        <RouterLink to="/login">
          Connexion
        </RouterLink>

        <RouterLink to="/register">
          Inscription
        </RouterLink>
      </template>

      <template v-else>
        <RouterLink to="/orders">
          Mes commandes
        </RouterLink>

        <span>
          Bonjour {{ authStore.user.name }}
        </span>

        <button @click="handleLogout">
          Déconnexion
        </button>
      </template>
    </nav>
  </header>

  <RouterView />
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from './stores/cart.js'
import { useAuthStore } from './stores/auth.js'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

onMounted(() => {
  authStore.fetchCurrentUser()
})

async function handleLogout() {
  await authStore.logout()
  router.push('/')
}
</script>