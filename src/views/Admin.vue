```vue
<template>
  <main>
    <h1>Tableau de bord administrateur</h1>

    <p>
      Bienvenue {{ authStore.user?.name }}.
    </p>

    <hr>

    <!-- PRODUITS -->
    <section>
      <h2>Produits</h2>

      <button @click="showProductForm = !showProductForm">
        {{ showProductForm ? 'Fermer' : 'Ajouter un produit' }}
      </button>

      <!-- AJOUT PRODUIT -->
      <form
        v-if="showProductForm"
        @submit.prevent="createProduct"
      >
        <div>
          <label>Nom</label>

          <input
            v-model="productForm.name"
            type="text"
            required
          >
        </div>

        <div>
          <label>Description</label>

          <textarea
            v-model="productForm.description"
          ></textarea>
        </div>

        <div>
          <label>Prix</label>

          <input
            v-model.number="productForm.price"
            type="number"
            min="1"
            required
          >
        </div>

        <div>
          <label>Photo du produit</label>

          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            @change="handleImageChange"
          >
        </div>

        <div>
          <label>Catégorie</label>

          <select
            v-model.number="productForm.category_id"
            required
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

        <button type="submit">
          Ajouter le produit
        </button>
      </form>

      <p v-if="productMessage">
        {{ productMessage }}
      </p>

      <p v-if="productError">
        {{ productError }}
      </p>

      <!-- LISTE PRODUITS -->
      <article
        v-for="product in products"
        :key="product.id"
      >
        <!-- MODIFICATION -->
        <template v-if="editingProductId === product.id">
          <h3>Modifier le produit</h3>

          <div>
            <label>Nom</label>

            <input
              v-model="editProductForm.name"
              type="text"
              required
            >
          </div>

          <div>
            <label>Description</label>

            <textarea
              v-model="editProductForm.description"
            ></textarea>
          </div>

          <div>
            <label>Prix</label>

            <input
              v-model.number="editProductForm.price"
              type="number"
              min="1"
              required
            >
          </div>

          <div>
            <label>Catégorie</label>

            <select
              v-model.number="editProductForm.category_id"
              required
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

          <button
            type="button"
            @click="updateProduct(product.id)"
          >
            Enregistrer
          </button>

          <button
            type="button"
            @click="cancelEditProduct"
          >
            Annuler
          </button>
        </template>

        <!-- AFFICHAGE PRODUIT -->
        <template v-else>
          <h3>{{ product.name }}</h3>

          <img
            v-if="product.image"
            :src="`${API_BASE_URL}${product.image}`"
            :alt="product.name"
            width="200"
          >

          <p v-else>
            Aucune photo
          </p>

          <p>
            {{ product.description }}
          </p>

          <p>
            {{ product.price }} FCFA
          </p>

          <p>
            Catégorie : {{ product.category }}
          </p>

          <button
            type="button"
            @click="startEditProduct(product)"
          >
            Modifier
          </button>

          <button
            type="button"
            @click="deleteProduct(product.id)"
          >
            Supprimer
          </button>
        </template>
      </article>
    </section>

    <hr>

    <!-- CATEGORIES -->
    <section>
      <h2>Catégories</h2>

      <button @click="showCategoryForm = !showCategoryForm">
        {{ showCategoryForm ? 'Fermer' : 'Ajouter une catégorie' }}
      </button>

      <form
        v-if="showCategoryForm"
        @submit.prevent="createCategory"
      >
        <div>
          <label>Nom de la catégorie</label>

          <input
            v-model="categoryName"
            type="text"
            placeholder="Exemple : Desserts"
            required
          >
        </div>

        <button type="submit">
          Ajouter
        </button>
      </form>

      <p v-if="categoryMessage">
        {{ categoryMessage }}
      </p>

      <p v-if="categoryError">
        {{ categoryError }}
      </p>

      <ul>
        <li
          v-for="category in categories"
          :key="category.id"
        >
          <template v-if="editingCategoryId === category.id">
            <input
              v-model="editCategoryName"
              type="text"
            >

            <button
              type="button"
              @click="updateCategory(category.id)"
            >
              Enregistrer
            </button>

            <button
              type="button"
              @click="cancelEditCategory"
            >
              Annuler
            </button>
          </template>

          <template v-else>
            {{ category.name }}

            <button
              type="button"
              @click="startEditCategory(category)"
            >
              Modifier
            </button>

            <button
              type="button"
              @click="deleteCategory(category.id)"
            >
              Supprimer
            </button>
          </template>
        </li>
      </ul>
    </section>

    <hr>

    <!-- COMMANDES -->
    <section>
      <h2>Commandes</h2>

      <p v-if="orderError">
        {{ orderError }}
      </p>

      <p v-if="orders.length === 0">
        Aucune commande.
      </p>

      <article
        v-for="order in orders"
        :key="order.id"
      >
        <h3>
          Commande #{{ order.id }}
        </h3>

        <p>
          Client : {{ order.customer_name }}
        </p>

        <p>
          Email : {{ order.customer_email }}
        </p>

        <p>
          Total : {{ order.total }} FCFA
        </p>

        <p>
          Date : {{ order.created_at }}
        </p>

        <label>
          Statut :
        </label>

        <select
          :value="order.status"
          @change="
            updateOrderStatus(
              order.id,
              $event.target.value
            )
          "
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
      </article>
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
```