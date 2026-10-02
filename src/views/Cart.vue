<template>
  <main>
    <h1>Mon panier</h1>

    <p v-if="cartStore.items.length === 0">
      Votre panier est vide.
    </p>

    <section v-else>
      <article
        v-for="item in cartStore.items"
        :key="item.id"
      >
        <h2>{{ item.name }}</h2>

        <p>{{ item.price }} FCFA</p>

        <div>
          <button @click="cartStore.decreaseQuantity(item.id)">
            −
          </button>

          <span>{{ item.quantity }}</span>

          <button @click="cartStore.increaseQuantity(item.id)">
            +
          </button>
        </div>

        <button @click="cartStore.removeFromCart(item.id)">
          Retirer
        </button>
      </article>

      <p>
        Total : {{ cartStore.totalPrice }} FCFA
      </p>

      <button @click="cartStore.clearCart">
        Vider le panier
      </button>

      <button
        @click="handleOrder"
        :disabled="loading"
      >
        {{ loading ? 'Création...' : 'Passer la commande' }}
      </button>

      <p v-if="message">
        {{ message }}
      </p>

      <p v-if="errorMessage">
        {{ errorMessage }}
      </p>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart.js'
import { useAuthStore } from '../stores/auth.js'
import { apiFetch } from '../services/api.js'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const loading = ref(false)
const message = ref('')
const errorMessage = ref('')

async function handleOrder() {
  message.value = ''
  errorMessage.value = ''

  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  loading.value = true

  try {
    const items = cartStore.items.map((item) => ({
      product_id: item.id,
      quantity: item.quantity
    }))

    const data = await apiFetch('/orders/create.php', {
      method: 'POST',
      body: JSON.stringify({ items })
    })

    message.value = `Commande #${data.order_id} créée avec succès.`

    cartStore.clearCart()
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    loading.value = false
  }
}
</script>