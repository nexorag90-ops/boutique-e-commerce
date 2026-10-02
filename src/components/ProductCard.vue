<template>
  <article>
    <h2>{{ product.name }}</h2>

    <p>{{ product.description }}</p>

    <p>{{ product.price }} FCFA</p>

    <p>{{ product.category }}</p>
  </article>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  }
})
</script>