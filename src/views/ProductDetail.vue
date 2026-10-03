<template>
  <main class="mx-auto max-w-5xl">
    <!-- CHARGEMENT -->
    <section
      v-if="loading"
      class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200"
    >
      <p class="text-gray-600">
        Chargement du produit...
      </p>
    </section>

    <!-- ERREUR -->
    <section
      v-else-if="errorMessage"
      class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center"
    >
      <h1 class="text-2xl font-bold text-red-700">
        Erreur
      </h1>

      <p class="mt-2 text-red-600">
        {{ errorMessage }}
      </p>
    </section>

    <!-- PRODUIT -->
    <section
      v-else-if="product"
      class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200"
    >
      <div class="grid gap-0 md:grid-cols-2">
        <!-- IMAGE -->
        <div class="flex min-h-[320px] items-center justify-center bg-gray-100 p-6">
          <img
            v-if="product.image"
            :src="`http://localhost:8000${product.image}`"
            :alt="product.name"
            class="max-h-[420px] w-full rounded-xl object-cover"
          />

          <p
            v-else
            class="text-gray-500"
          >
            Aucune photo disponible
          </p>
        </div>

        <!-- INFORMATIONS -->
        <div class="flex flex-col justify-center p-8">
          <span
            class="mb-4 w-fit rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700"
          >
            {{ product.category }}
          </span>

          <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            {{ product.name }}
          </h1>

          <p class="mt-4 leading-7 text-gray-600">
            {{ product.description }}
          </p>

          <p class="mt-6 text-3xl font-bold text-indigo-600">
            {{ product.price }} FCFA
          </p>

          <button
            type="button"
            class="mt-8 rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white transition hover:bg-indigo-700"
          >
            Ajouter au panier
          </button>
        </div>
      </div>
    </section>

    <!-- PRODUIT INTROUVABLE -->
    <section
      v-else
      class="rounded-2xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-200"
    >
      <h1 class="text-2xl font-bold text-gray-900">
        Produit introuvable
      </h1>

      <p class="mt-2 text-gray-500">
        Le produit demandé n'existe pas.
      </p>
    </section>
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