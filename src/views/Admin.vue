<template>
  <main>
    <h1>Espace administrateur</h1>

    <p>
      Bienvenue {{ authStore.user?.name }}.
    </p>

    <p>
      Cette page sera complétée dans le Bloc 5 avec le tableau de bord,
      les produits, les catégories et les commandes.
    </p>
  </main>
</template>

<script setup>
import { useAuthStore } from '../stores/auth.js'

const authStore = useAuthStore()
</script>