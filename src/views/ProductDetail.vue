<template>
  <main v-if="loading">
    <h1>Chargement du produit...</h1>
  </main>

  <main v-else-if="errorMessage">
    <h1>Erreur</h1>
    <p>{{ errorMessage }}</p>
  </main>

  <main v-else-if="product">
    <h1>{{ product.name }}</h1>

    <p>{{ product.description }}</p>

    <p>{{ product.price }} FCFA</p>

    <p>{{ product.category }}</p>
  </main>

  <main v-else>
    <h1>Produit introuvable</h1>

    <p>Le produit demandé n'existe pas.</p>
  </main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { apiFetch } from '../services/api.js'

const route = useRoute()

const product = ref(null)
const loading = ref(false)
const errorMessage = ref('')

async function fetchProduct() {
  loading.value = true
  errorMessage.value = ''

  try {
    const data = await apiFetch(
      `/products/show.php?id=${route.params.id}`
    )

    product.value = data.product
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProduct()
})
</script>