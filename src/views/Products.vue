<template>
  <main>
    <h1>Nos produits</h1>

    <p>Découvrez tous nos produits disponibles.</p>

    <section>
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </section>
  </main>
</template>

<script setup>
import ProductCard from '../components/ProductCard.vue'
import { products } from '../data/products.js'
</script>