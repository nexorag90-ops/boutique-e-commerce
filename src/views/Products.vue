<template>
  <main>
    <section class="mb-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Nos produits
      </h1>

      <p class="mt-2 text-gray-600">
        Découvrez notre sélection de produits.
      </p>
    </section>

    <!-- FILTRES -->
    <section
      class="mb-8 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200"
    >
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div>
          <label
            for="search"
            class="mb-2 block text-sm font-medium text-gray-700"
          >
            Rechercher
          </label>

          <input
            id="search"
            v-model="search"
            type="text"
            placeholder="Nom du produit..."
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
          >
        </div>

        <div>
          <label
            for="category"
            class="mb-2 block text-sm font-medium text-gray-700"
          >
            Catégorie
          </label>

          <select
            id="category"
            v-model="selectedCategory"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
          >
            <option value="">
              Toutes les catégories
            </option>

            <option
              v-for="category in categories"
              :key="category.id"
              :value="String(category.id)"
            >
              {{ category.name }}
            </option>
          </select>
        </div>

        <div>
          <label
            for="minPrice"
            class="mb-2 block text-sm font-medium text-gray-700"
          >
            Prix minimum
          </label>

          <input
            id="minPrice"
            v-model.number="minPrice"
            type="number"
            min="0"
            placeholder="0"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
          >
        </div>

        <div>
          <label
            for="maxPrice"
            class="mb-2 block text-sm font-medium text-gray-700"
          >
            Prix maximum
          </label>

          <input
            id="maxPrice"
            v-model.number="maxPrice"
            type="number"
            min="0"
            placeholder="Prix maximum"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
          >
        </div>
      </div>
    </section>

    <!-- CHARGEMENT -->
    <div
      v-if="loading"
      class="rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-gray-200"
    >
      <p class="text-gray-600">
        Chargement des produits...
      </p>
    </div>

    <!-- ERREUR -->
    <div
      v-else-if="error"
      class="rounded-2xl border border-red-200 bg-red-50 p-6 text-center"
    >
      <p class="font-medium text-red-700">
        {{ error }}
      </p>
    </div>

    <!-- AUCUN PRODUIT -->
    <div
      v-else-if="filteredProducts.length === 0"
      class="rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-gray-200"
    >
      <p class="text-lg font-medium text-gray-700">
        Aucun produit trouvé.
      </p>

      <p class="mt-2 text-sm text-gray-500">
        Essayez de modifier vos critères de recherche.
      </p>
    </div>

    <!-- PRODUITS -->
    <section
      v-else
      class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
    >
      <ProductCard
        v-for="product in filteredProducts"
        :key="product.id"
        :product="product"
      />
    </section>
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

const loading = ref(true)
const error = ref('')

const categories = computed(() => {
  const categoryMap = new Map()

  products.value.forEach((product) => {
    if (
      product.category_id &&
      product.category
    ) {
      categoryMap.set(
        product.category_id,
        product.category
      )
    }
  })

  return Array.from(categoryMap.entries()).map(
    ([id, name]) => ({
      id,
      name
    })
  )
})

const filteredProducts = computed(() => {
  return products.value.filter((product) => {
    const matchesSearch =
      product.name
        .toLowerCase()
        .includes(search.value.toLowerCase())

    const matchesCategory =
      selectedCategory.value === '' ||
      String(product.category_id) === selectedCategory.value

    const matchesMinPrice =
      minPrice.value === null ||
      minPrice.value === '' ||
      Number(product.price) >= Number(minPrice.value)

    const matchesMaxPrice =
      maxPrice.value === null ||
      maxPrice.value === '' ||
      Number(product.price) <= Number(maxPrice.value)

    return (
      matchesSearch &&
      matchesCategory &&
      matchesMinPrice &&
      matchesMaxPrice
    )
  })
})

async function loadProducts() {
  const data = await apiFetch('/products/list.php')

  products.value = data.products
}

onMounted(async () => {
  try {
    loading.value = true
    error.value = ''

    await loadProducts()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
})
</script>