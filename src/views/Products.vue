<template>
  <main>
    <h1>Nos produits</h1>

    <p>Découvrez tous nos produits disponibles.</p>

    <input
      v-model="search"
      type="text"
      placeholder="Rechercher un produit..."
    />

    <section>
      <ProductCard
        v-for="product in filteredProducts"
        :key="product.id"
        :product="product"
      />
    </section>

    <p v-if="filteredProducts.length === 0">
      Aucun produit trouvé.
    </p>
  </main>
</template>

<script setup>
import { computed, ref } from 'vue'
import ProductCard from '../components/ProductCard.vue'
import { products } from '../data/products.js'

const search = ref('')

const filteredProducts = computed(() => {
  return products.filter((product) =>
    product.name.toLowerCase().includes(search.value.toLowerCase())
  )
})
</script>