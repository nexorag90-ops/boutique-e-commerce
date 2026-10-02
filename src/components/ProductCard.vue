<template>
  <article>
    <h2>{{ product.name }}</h2>

    <p>{{ product.description }}</p>

    <p>{{ product.price }} FCFA</p>

    <p>{{ product.category }}</p>

    <button @click="cartStore.addToCart(product)">
      Ajouter au panier
    </button>
  </article>
</template>

<script setup>
import { useCartStore } from '../stores/cart.js'

defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
</script>