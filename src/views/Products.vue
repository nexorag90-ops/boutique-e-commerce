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

    <p v-if="loading">
      Chargement des produits...
    </p>

    <p v-if="errorMessage">
      {{ errorMessage }}
    </p>

    <section v-if="!loading && !errorMessage">
      <ProductCard
        v-for="product in filteredProducts"
        :key="product.id"
        :product="product"
      />
    </section>

    <p v-if="!loading && !errorMessage && filteredProducts.length === 0">
      Aucun produit trouvé.
    </p>
  </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import ProductCard from '../components/ProductCard.vue'
import { apiFetch } from '../services/api.js'

const products = ref([])
const search = ref('')
const selectedCategory = ref('')
const minPrice = ref(null)
const maxPrice = ref(null)

const loading = ref(false)
const errorMessage = ref('')

const categories = computed(() => {
  return [...new Set(products.value.map((product) => product.category))]
})

const filteredProducts = computed(() => {
  return products.value.filter((product) => {
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

async function fetchProducts() {
  loading.value = true
  errorMessage.value = ''

  try {
    const data = await apiFetch('/products/list.php')
    products.value = data.products
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
})
</script>