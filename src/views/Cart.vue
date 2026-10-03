<template>
  <main class="mx-auto max-w-5xl">
    <!-- TITRE -->
    <section class="mb-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Mon panier
      </h1>

      <p class="mt-2 text-gray-600">
        Vérifiez vos articles avant de passer votre commande.
      </p>
    </section>

    <!-- PANIER VIDE -->
    <section
      v-if="cartStore.items.length === 0"
      class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200"
    >
      <div class="text-5xl">
        🛒
      </div>

      <h2 class="mt-4 text-xl font-semibold text-gray-900">
        Votre panier est vide
      </h2>

      <p class="mt-2 text-gray-500">
        Ajoutez des produits pour commencer votre commande.
      </p>

      <RouterLink
        to="/products"
        class="mt-6 inline-block rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700"
      >
        Voir les produits
      </RouterLink>
    </section>

    <!-- PANIER -->
    <section
      v-else
      class="grid gap-6 lg:grid-cols-[1fr_320px]"
    >
      <!-- ARTICLES -->
      <div class="space-y-4">
        <article
          v-for="item in cartStore.items"
          :key="item.id"
          class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200"
        >
          <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <!-- INFORMATIONS -->
            <div>
              <h2 class="text-lg font-semibold text-gray-900">
                {{ item.name }}
              </h2>

              <p class="mt-1 font-medium text-indigo-600">
                {{ item.price }} FCFA
              </p>
            </div>

            <!-- QUANTITÉ -->
            <div class="flex items-center gap-3">
              <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 text-lg font-semibold text-gray-700 transition hover:bg-gray-100"
                @click="cartStore.decreaseQuantity(item.id)"
              >
                −
              </button>

              <span class="min-w-8 text-center font-semibold text-gray-900">
                {{ item.quantity }}
              </span>

              <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 text-lg font-semibold text-gray-700 transition hover:bg-gray-100"
                @click="cartStore.increaseQuantity(item.id)"
              >
                +
              </button>
            </div>

            <!-- RETIRER -->
            <button
              type="button"
              class="text-sm font-medium text-red-600 transition hover:text-red-700"
              @click="cartStore.removeFromCart(item.id)"
            >
              Retirer
            </button>
          </div>
        </article>
      </div>

      <!-- RÉSUMÉ -->
      <aside
        class="h-fit rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 lg:sticky lg:top-6"
      >
        <h2 class="text-xl font-bold text-gray-900">
          Résumé
        </h2>

        <div class="mt-5 flex items-center justify-between border-b border-gray-200 pb-4">
          <span class="text-gray-600">
            Articles
          </span>

          <span class="font-medium text-gray-900">
            {{ cartStore.totalItems }}
          </span>
        </div>

        <div class="flex items-center justify-between py-4">
          <span class="font-semibold text-gray-900">
            Total
          </span>

          <span class="text-xl font-bold text-indigo-600">
            {{ cartStore.totalPrice }} FCFA
          </span>
        </div>

        <button
          type="button"
          class="w-full rounded-xl border border-gray-300 px-4 py-3 font-semibold text-gray-700 transition hover:bg-gray-100"
          @click="cartStore.clearCart"
        >
          Vider le panier
        </button>

        <button
          type="button"
          class="mt-3 w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
          :disabled="loading"
          @click="handleOrder"
        >
          {{ loading ? 'Création...' : 'Passer la commande' }}
        </button>

        <!-- SUCCÈS -->
        <p
          v-if="message"
          class="mt-4 rounded-lg bg-green-50 p-3 text-sm font-medium text-green-700"
        >
          {{ message }}
        </p>

        <!-- ERREUR -->
        <p
          v-if="errorMessage"
          class="mt-4 rounded-lg bg-red-50 p-3 text-sm font-medium text-red-700"
        >
          {{ errorMessage }}
        </p>
      </aside>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart.js'
import { useAuthStore } from '../stores/auth.js'
import { apiFetch } from '../services/api.js'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const loading = ref(false)
const message = ref('')
const errorMessage = ref('')

async function handleOrder() {
  message.value = ''
  errorMessage.value = ''

  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  loading.value = true

  try {
    const items = cartStore.items.map((item) => ({
      product_id: item.id,
      quantity: item.quantity
    }))

    const data = await apiFetch('/orders/create.php', {
      method: 'POST',
      body: JSON.stringify({ items })
    })

    message.value = `Commande #${data.order_id} créée avec succès.`

    cartStore.clearCart()
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    loading.value = false
  }
}
</script>