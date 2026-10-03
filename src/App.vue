<template>
  <div class="min-h-screen bg-gray-50 text-gray-900">
    <header class="border-b bg-white shadow-sm">
      <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4">
        <RouterLink
          to="/"
          class="text-xl font-bold text-indigo-600"
        >
          Boutique
        </RouterLink>

        <div class="flex flex-wrap items-center gap-3">
          <RouterLink
            to="/"
            class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100"
          >
            Accueil
          </RouterLink>

          <RouterLink
            to="/products"
            class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100"
          >
            Produits
          </RouterLink>

          <RouterLink
            to="/cart"
            class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700"
          >
            Panier ({{ cartStore.totalItems }})
          </RouterLink>

          <template v-if="!authStore.isAuthenticated">
            <RouterLink
              to="/login"
              class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100"
            >
              Connexion
            </RouterLink>

            <RouterLink
              to="/register"
              class="rounded-lg border border-indigo-600 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50"
            >
              Inscription
            </RouterLink>
          </template>

          <template v-else>
            <RouterLink
              to="/orders"
              class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-gray-100"
            >
              Mes commandes
            </RouterLink>

            <span class="text-sm text-gray-600">
              Bonjour {{ authStore.user.name }}
            </span>

            <button
              type="button"
              class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700"
              @click="handleLogout"
            >
              Déconnexion
            </button>
          </template>
        </div>
      </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6">
      <RouterView />
    </main>
  </div>
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