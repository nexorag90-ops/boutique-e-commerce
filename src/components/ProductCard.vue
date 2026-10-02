```vue
<template>
  <article>
    <img
      v-if="product.image"
      :src="`${API_BASE_URL}${product.image}`"
      :alt="product.name"
      width="250"
    />

    <p v-else>
      Aucune photo
    </p>

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

const API_BASE_URL = 'http://localhost:8000'

defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
</script>
```