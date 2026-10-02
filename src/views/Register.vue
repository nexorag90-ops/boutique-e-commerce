<template>
  <main>
    <h1>Créer un compte</h1>

    <form @submit.prevent="handleRegister">
      <div>
        <label for="name">Nom</label>

        <input
          id="name"
          v-model="name"
          type="text"
          placeholder="Votre nom"
          required
        />
      </div>

      <div>
        <label for="email">Email</label>

        <input
          id="email"
          v-model="email"
          type="email"
          placeholder="Votre email"
          required
        />
      </div>

      <div>
        <label for="password">Mot de passe</label>

        <input
          id="password"
          v-model="password"
          type="password"
          placeholder="Minimum 6 caractères"
          minlength="6"
          required
        />
      </div>

      <p v-if="errorMessage">
        {{ errorMessage }}
      </p>

      <p v-if="successMessage">
        {{ successMessage }}
      </p>

      <button type="submit" :disabled="authStore.loading">
        {{ authStore.loading ? 'Création...' : 'Créer mon compte' }}
      </button>
    </form>

    <p>
      Vous avez déjà un compte ?
      <RouterLink to="/login">
        Se connecter
      </RouterLink>
    </p>
  </main>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth.js'

const router = useRouter()
const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const successMessage = ref('')

async function handleRegister() {
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await authStore.register(
      name.value,
      email.value,
      password.value
    )

    successMessage.value = 'Compte créé avec succès.'

    setTimeout(() => {
      router.push('/login')
    }, 1000)
  } catch (error) {
    errorMessage.value = error.message
  }
}
</script>