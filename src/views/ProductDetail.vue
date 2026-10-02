<template>
  <main v-if="product">
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
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { products } from '../data/products.js'

const route = useRoute()

const product = computed(() => {
  return products.find(
    (product) => product.id === Number(route.params.id)
  )
})
</script>