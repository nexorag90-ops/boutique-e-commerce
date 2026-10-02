<template>
  <main>
    <h1>Nos produits</h1>

    <p>Découvrez tous nos produits disponibles.</p>

    <input
      v-model="search"
      type="text"
      placeholder="Rechercher un produit..."
    />

    <select v-model="selectedCategory">
      <option value="">Toutes les catégories</option>
      <option
        v-for="category in categories"
        :key="category"
        :value="category"
      >
        {{ category }}
      </option>
    </select>

    <input
      v-model.number="minPrice"
      type="number"
      placeholder="Prix minimum"
      min="0"
    />

    <input
      v-model.number="maxPrice"
      type="number"
      placeholder="Prix maximum"
      min="0"
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
const selectedCategory = ref('')
const minPrice = ref(null)
const maxPrice = ref(null)

const categories = computed(() => {
  return [...new Set(products.map((product) => product.category))]
})

const filteredProducts = computed(() => {
  return products.filter((product) => {
    const matchesSearch = product.name
      .toLowerCase()
      .includes(search.value.toLowerCase())

    const matchesCategory =
      selectedCategory.value === '' ||
      product.category === selectedCategory.value

    const matchesMinPrice =
      minPrice.value === null ||
      minPrice.value === '' ||
      product.price >= minPrice.value

    const matchesMaxPrice =
      maxPrice.value === null ||
      maxPrice.value === '' ||
      product.price <= maxPrice.value

    return (
      matchesSearch &&
      matchesCategory &&
      matchesMinPrice &&
      matchesMaxPrice
    )
  })
})
</script>