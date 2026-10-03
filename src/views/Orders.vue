<template>
  <main class="mx-auto max-w-5xl">
    <!-- TITRE -->
    <section class="mb-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Mes commandes
      </h1>

      <p class="mt-2 text-gray-600">
        Retrouvez ici l'historique de vos commandes.
      </p>
    </section>

    <!-- CHARGEMENT -->
    <section
      v-if="loading"
      class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200"
    >
      <p class="text-gray-600">
        Chargement des commandes...
      </p>
    </section>

    <!-- ERREUR -->
    <section
      v-else-if="errorMessage"
      class="rounded-2xl border border-red-200 bg-red-50 p-6"
    >
      <p class="font-medium text-red-700">
        {{ errorMessage }}
      </p>
    </section>

    <!-- AUCUNE COMMANDE -->
    <section
      v-else-if="orders.length === 0"
      class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200"
    >
      <div class="text-5xl">
        📦
      </div>

      <h2 class="mt-4 text-xl font-semibold text-gray-900">
        Aucune commande
      </h2>

      <p class="mt-2 text-gray-500">
        Vous n'avez encore passé aucune commande.
      </p>

      <RouterLink
        to="/products"
        class="mt-6 inline-block rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700"
      >
        Découvrir les produits
      </RouterLink>
    </section>

    <!-- COMMANDES -->
    <section
      v-else
      class="space-y-5"
    >
      <article
        v-for="order in orders"
        :key="order.id"
        class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200"
      >
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
          <div>
            <h2 class="text-xl font-bold text-gray-900">
              Commande #{{ order.id }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              {{ order.created_at }}
            </p>
          </div>

          <span
            class="w-fit rounded-full bg-yellow-100 px-3 py-1 text-sm font-semibold text-yellow-700"
          >
            {{ order.status }}
          </span>
        </div>

        <div class="mt-6 border-t border-gray-100 pt-5">
          <p class="text-sm text-gray-500">
            Total de la commande
          </p>

          <p class="mt-1 text-2xl font-bold text-indigo-600">
            {{ order.total }} FCFA
          </p>
        </div>
      </article>
    </section>
  </main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { apiFetch } from '../services/api.js'

const orders = ref([])
const loading = ref(true)
const errorMessage = ref('')

async function loadOrders() {
  try {
    const data = await apiFetch('/order/my-orders.php')
    orders.value = data.orders
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadOrders()
})
</script>