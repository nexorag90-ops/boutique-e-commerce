<template>
  <main class="mx-auto max-w-7xl space-y-8">

    <!-- EN-TÊTE -->
    <section class="rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 p-6 text-white shadow-lg">
      <h1 class="text-3xl font-bold tracking-tight">
        Tableau de bord administrateur
      </h1>

      <p class="mt-2 text-indigo-100">
        Bienvenue {{ authStore.user?.name }}.
      </p>
    </section>

    <!-- PRODUITS -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            Produits
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Gérez votre catalogue de produits.
          </p>
        </div>

        <button
          type="button"
          class="rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white transition hover:bg-indigo-700"
          @click="showProductForm = !showProductForm"
        >
          {{ showProductForm ? 'Fermer' : 'Ajouter un produit' }}
        </button>
      </div>

      <!-- AJOUT PRODUIT -->
      <form
        v-if="showProductForm"
        class="mt-6 space-y-5 rounded-2xl bg-gray-50 p-5 ring-1 ring-gray-200"
        @submit.prevent="createProduct"
      >
        <h3 class="text-lg font-bold text-gray-900">
          Ajouter un produit
        </h3>

        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Nom
            </label>

            <input
              v-model="productForm.name"
              type="text"
              required
              class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Prix
            </label>

            <input
              v-model.number="productForm.price"
              type="number"
              min="1"
              required
              class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            />
          </div>
        </div>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Description
          </label>

          <textarea
            v-model="productForm.description"
            rows="4"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
          ></textarea>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Photo du produit
            </label>

            <input
              type="file"
              accept="image/jpeg,image/png,image/webp"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
              @change="handleImageChange"
            />
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Catégorie
            </label>

            <select
              v-model.number="productForm.category_id"
              required
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            >
              <option :value="0">
                Choisir une catégorie
              </option>

              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
          </div>
        </div>

        <button
          type="submit"
          class="rounded-xl bg-green-600 px-5 py-3 font-semibold text-white transition hover:bg-green-700"
        >
          Ajouter le produit
        </button>
      </form>

      <p
        v-if="productMessage"
        class="mt-5 rounded-xl bg-green-50 p-4 text-sm font-medium text-green-700"
      >
        {{ productMessage }}
      </p>

      <p
        v-if="productError"
        class="mt-5 rounded-xl bg-red-50 p-4 text-sm font-medium text-red-700"
      >
        {{ productError }}
      </p>

      <!-- LISTE PRODUITS -->
      <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <article
          v-for="product in products"
          :key="product.id"
          class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
        >
          <!-- MODIFICATION -->
          <template v-if="editingProductId === product.id">
            <div class="space-y-4 p-5">
              <h3 class="text-lg font-bold text-gray-900">
                Modifier le produit
              </h3>

              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                  Nom
                </label>

                <input
                  v-model="editProductForm.name"
                  type="text"
                  required
                  class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                  Description
                </label>

                <textarea
                  v-model="editProductForm.description"
                  rows="4"
                  class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                ></textarea>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                  Prix
                </label>

                <input
                  v-model.number="editProductForm.price"
                  type="number"
                  min="1"
                  required
                  class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                  Catégorie
                </label>

                <select
                  v-model.number="editProductForm.category_id"
                  required
                  class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
                  <option :value="0">
                    Choisir une catégorie
                  </option>

                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </div>

              <div class="flex flex-wrap gap-3">
                <button
                  type="button"
                  class="rounded-xl bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700"
                  @click="updateProduct(product.id)"
                >
                  Enregistrer
                </button>

                <button
                  type="button"
                  class="rounded-xl border border-gray-300 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-100"
                  @click="cancelEditProduct"
                >
                  Annuler
                </button>
              </div>
            </div>
          </template>

          <!-- AFFICHAGE PRODUIT -->
          <template v-else>
            <div class="aspect-video bg-gray-100">
              <img
                v-if="product.image"
                :src="`${API_BASE_URL}${product.image}`"
                :alt="product.name"
                class="h-full w-full object-cover"
              />

              <div
                v-else
                class="flex h-full items-center justify-center text-sm text-gray-500"
              >
                Aucune photo
              </div>
            </div>

            <div class="p-5">
              <div class="flex items-start justify-between gap-3">
                <h3 class="text-lg font-bold text-gray-900">
                  {{ product.name }}
                </h3>

                <span
                  class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700"
                >
                  {{ product.category }}
                </span>
              </div>

              <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                {{ product.description }}
              </p>

              <p class="mt-4 text-xl font-bold text-indigo-600">
                {{ product.price }} FCFA
              </p>

              <div class="mt-5 flex flex-wrap gap-3">
                <button
                  type="button"
                  class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600"
                  @click="startEditProduct(product)"
                >
                  Modifier
                </button>

                <button
                  type="button"
                  class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                  @click="deleteProduct(product.id)"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </template>
        </article>
      </div>
    </section>

    <!-- CATÉGORIES -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            Catégories
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Organisez vos produits.
          </p>
        </div>

        <button
          type="button"
          class="rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white transition hover:bg-indigo-700"
          @click="showCategoryForm = !showCategoryForm"
        >
          {{ showCategoryForm ? 'Fermer' : 'Ajouter une catégorie' }}
        </button>
      </div>

      <form
        v-if="showCategoryForm"
        class="mt-6 flex flex-col gap-3 sm:flex-row"
        @submit.prevent="createCategory"
      >
        <input
          v-model="categoryName"
          type="text"
          placeholder="Exemple : Desserts"
          required
          class="flex-1 rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
        />

        <button
          type="submit"
          class="rounded-xl bg-green-600 px-5 py-3 font-semibold text-white transition hover:bg-green-700"
        >
          Ajouter
        </button>
      </form>

      <p
        v-if="categoryMessage"
        class="mt-4 rounded-xl bg-green-50 p-4 text-sm font-medium text-green-700"
      >
        {{ categoryMessage }}
      </p>

      <p
        v-if="categoryError"
        class="mt-4 rounded-xl bg-red-50 p-4 text-sm font-medium text-red-700"
      >
        {{ categoryError }}
      </p>

      <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="category in categories"
          :key="category.id"
          class="rounded-xl border border-gray-200 p-4"
        >
          <template v-if="editingCategoryId === category.id">
            <div class="space-y-3">
              <input
                v-model="editCategoryName"
                type="text"
                class="w-full rounded-xl border border-gray-300 px-4 py-2 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
              />

              <div class="flex flex-wrap gap-2">
                <button
                  type="button"
                  class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                  @click="updateCategory(category.id)"
                >
                  Enregistrer
                </button>

                <button
                  type="button"
                  class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                  @click="cancelEditCategory"
                >
                  Annuler
                </button>
              </div>
            </div>
          </template>

          <template v-else>
            <div class="flex items-center justify-between gap-3">
              <span class="font-semibold text-gray-900">
                {{ category.name }}
              </span>

              <div class="flex gap-2">
                <button
                  type="button"
                  class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                  @click="startEditCategory(category)"
                >
                  Modifier
                </button>

                <button
                  type="button"
                  class="text-sm font-semibold text-red-600 transition hover:text-red-800"
                  @click="deleteCategory(category.id)"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>
    </section>

    <!-- COMMANDES -->
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
          Commandes
        </h2>

        <p class="mt-1 text-sm text-gray-500">
          Consultez et gérez les commandes des clients.
        </p>
      </div>

      <p
        v-if="orderError"
        class="mb-5 rounded-xl bg-red-50 p-4 text-sm font-medium text-red-700"
      >
        {{ orderError }}
      </p>

      <div
        v-if="orders.length === 0"
        class="rounded-xl bg-gray-50 p-8 text-center text-gray-500"
      >
        Aucune commande.
      </div>

      <div
        v-else
        class="grid gap-5 lg:grid-cols-2"
      >
        <article
          v-for="order in orders"
          :key="order.id"
          class="rounded-2xl border border-gray-200 p-5 transition hover:shadow-sm"
        >
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-bold text-gray-900">
                Commande #{{ order.id }}
              </h3>

              <p class="mt-1 text-sm text-gray-500">
                {{ order.created_at }}
              </p>
            </div>

            <span
              class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700"
            >
              {{ order.status }}
            </span>
          </div>

          <div class="mt-5 space-y-2 text-sm text-gray-600">
            <p>
              <span class="font-semibold text-gray-700">
                Client :
              </span>
              {{ order.customer_name }}
            </p>

            <p>
              <span class="font-semibold text-gray-700">
                Email :
              </span>
              {{ order.customer_email }}
            </p>

            <p>
              <span class="font-semibold text-gray-700">
                Total :
              </span>

              <span class="font-bold text-indigo-600">
                {{ order.total }} FCFA
              </span>
            </p>
          </div>

          <div class="mt-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Statut
            </label>

            <select
              :value="order.status"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
              @change="updateOrderStatus(order.id, $event.target.value)"
            >
              <option value="pending">
                En attente
              </option>

              <option value="confirmed">
                Confirmée
              </option>

              <option value="completed">
                Terminée
              </option>

              <option value="cancelled">
                Annulée
              </option>
            </select>
          </div>
        </article>
      </div>
    </section>

  </main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useAuthStore } from '../stores/auth.js'
import { apiFetch } from '../services/api.js'

const API_BASE_URL = 'http://localhost:8000'

const authStore = useAuthStore()

const products = ref([])
const categories = ref([])
const orders = ref([])

const showProductForm = ref(false)
const showCategoryForm = ref(false)

const productMessage = ref('')
const productError = ref('')

const categoryName = ref('')
const categoryMessage = ref('')
const categoryError = ref('')

const orderError = ref('')

const editingProductId = ref(null)

const editingCategoryId = ref(null)
const editCategoryName = ref('')

const selectedImage = ref(null)

const productForm = reactive({
  name: '',
  description: '',
  price: 0,
  category_id: 0
})

const editProductForm = reactive({
  name: '',
  description: '',
  price: 0,
  category_id: 0
})

async function loadProducts() {
  const data = await apiFetch(
    '/admin/products-list.php'
  )

  products.value = data.products
}

async function loadCategories() {
  const data = await apiFetch(
    '/admin/categories-list.php'
  )

  categories.value = data.categories
}

async function loadOrders() {
  const data = await apiFetch(
    '/admin/orders-list.php'
  )

  orders.value = data.orders
}

function handleImageChange(event) {
  selectedImage.value = event.target.files[0] || null
}

async function createProduct() {
  productMessage.value = ''
  productError.value = ''

  try {
    const formData = new FormData()

    formData.append('name', productForm.name)
    formData.append(
      'description',
      productForm.description
    )
    formData.append('price', productForm.price)
    formData.append(
      'category_id',
      productForm.category_id
    )

    if (selectedImage.value) {
      formData.append(
        'image',
        selectedImage.value
      )
    }

    const data = await apiFetch(
      '/admin/product-create.php',
      {
        method: 'POST',
        body: formData
      }
    )

    productMessage.value = data.message

    productForm.name = ''
    productForm.description = ''
    productForm.price = 0
    productForm.category_id = 0

    selectedImage.value = null

    showProductForm.value = false

    await loadProducts()
  } catch (error) {
    productError.value = error.message
  }
}

function startEditProduct(product) {
  editingProductId.value = product.id

  editProductForm.name = product.name
  editProductForm.description = product.description
  editProductForm.price = Number(product.price)
  editProductForm.category_id = Number(
    product.category_id
  )

  productMessage.value = ''
  productError.value = ''
}

function cancelEditProduct() {
  editingProductId.value = null

  editProductForm.name = ''
  editProductForm.description = ''
  editProductForm.price = 0
  editProductForm.category_id = 0
}

async function updateProduct(productId) {
  productMessage.value = ''
  productError.value = ''

  try {
    const data = await apiFetch(
      '/admin/product-update.php',
      {
        method: 'PUT',
        body: JSON.stringify({
          id: productId,
          name: editProductForm.name,
          description: editProductForm.description,
          price: editProductForm.price,
          category_id: editProductForm.category_id
        })
      }
    )

    productMessage.value = data.message

    cancelEditProduct()

    await loadProducts()
  } catch (error) {
    productError.value = error.message
  }
}

async function deleteProduct(productId) {
  if (
    !confirm(
      'Voulez-vous vraiment supprimer ce produit ?'
    )
  ) {
    return
  }

  productMessage.value = ''
  productError.value = ''

  try {
    const data = await apiFetch(
      '/admin/product-delete.php',
      {
        method: 'DELETE',
        body: JSON.stringify({
          id: productId
        })
      }
    )

    productMessage.value = data.message

    await loadProducts()
  } catch (error) {
    productError.value = error.message
  }
}

async function createCategory() {
  categoryMessage.value = ''
  categoryError.value = ''

  try {
    const data = await apiFetch(
      '/admin/category-create.php',
      {
        method: 'POST',
        body: JSON.stringify({
          name: categoryName.value
        })
      }
    )

    categoryMessage.value = data.message

    categoryName.value = ''

    showCategoryForm.value = false

    await loadCategories()
  } catch (error) {
    categoryError.value = error.message
  }
}

function startEditCategory(category) {
  editingCategoryId.value = category.id
  editCategoryName.value = category.name

  categoryMessage.value = ''
  categoryError.value = ''
}

function cancelEditCategory() {
  editingCategoryId.value = null
  editCategoryName.value = ''
}

async function updateCategory(categoryId) {
  categoryMessage.value = ''
  categoryError.value = ''

  try {
    const data = await apiFetch(
      '/admin/category-update.php',
      {
        method: 'PUT',
        body: JSON.stringify({
          id: categoryId,
          name: editCategoryName.value
        })
      }
    )

    categoryMessage.value = data.message

    cancelEditCategory()

    await loadCategories()
    await loadProducts()
  } catch (error) {
    categoryError.value = error.message
  }
}

async function deleteCategory(categoryId) {
  if (
    !confirm(
      'Voulez-vous vraiment supprimer cette catégorie ?'
    )
  ) {
    return
  }

  categoryMessage.value = ''
  categoryError.value = ''

  try {
    const data = await apiFetch(
      '/admin/category-delete.php',
      {
        method: 'DELETE',
        body: JSON.stringify({
          id: categoryId
        })
      }
    )

    categoryMessage.value = data.message

    await loadCategories()
  } catch (error) {
    categoryError.value = error.message
  }
}

async function updateOrderStatus(orderId, status) {
  orderError.value = ''

  try {
    await apiFetch(
      '/admin/order-status.php',
      {
        method: 'PUT',
        body: JSON.stringify({
          order_id: orderId,
          status
        })
      }
    )

    const order = orders.value.find(
      (item) => item.id === orderId
    )

    if (order) {
      order.status = status
    }
  } catch (error) {
    orderError.value = error.message
  }
}

onMounted(async () => {
  try {
    await Promise.all([
      loadProducts(),
      loadCategories(),
      loadOrders()
    ])
  } catch (error) {
    productError.value = error.message
  }
})
</script>