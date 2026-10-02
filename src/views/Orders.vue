<template>
  <main>
    <h1>Mes commandes</h1>

    <p v-if="loading">
      Chargement des commandes...
    </p>

    <p v-else-if="errorMessage">
      {{ errorMessage }}
    </p>

    <p v-else-if="orders.length === 0">
      Vous n'avez encore passé aucune commande.
    </p>

    <section v-else>
      <article
        v-for="order in orders"
        :key="order.id"
      >
        <h2>
          Commande #{{ order.id }}
        </h2>

        <p>
          Total : {{ order.total }} FCFA
        </p>

        <p>
          Statut : {{ order.status }}
        </p>

        <p>
          Date : {{ order.created_at }}
        </p>
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