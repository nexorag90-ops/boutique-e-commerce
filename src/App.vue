<template>
  <header>
    <nav>
      <RouterLink to="/">Accueil</RouterLink>
      <RouterLink to="/products">Produits</RouterLink>
      <RouterLink to="/cart">
        Panier ({{ cartStore.totalItems }})
      </RouterLink>
    </nav>
  </header>

  <RouterView />
</template>

<script setup>
import { useCartStore } from './stores/cart.js'

const cartStore = useCartStore()
</script>