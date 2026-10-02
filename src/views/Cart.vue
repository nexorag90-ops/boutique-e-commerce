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

        <p>Quantité : {{ item.quantity }}</p>

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
    </section>
  </main>
</template>

<script setup>
import { useCartStore } from '../stores/cart.js'

const cartStore = useCartStore()
</script>