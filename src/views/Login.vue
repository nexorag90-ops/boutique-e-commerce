<template>
  <main>
    <h1>Connexion</h1>

    <form @submit.prevent="handleLogin">
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
          placeholder="Votre mot de passe"
          required
        />
      </div>

      <p v-if="errorMessage">
        {{ errorMessage }}
      </p>

      <button type="submit" :disabled="authStore.loading">
        {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
      </button>
    </form>

    <p>
      Vous n'avez pas encore de compte ?
      <RouterLink to="/register">
        Créer un compte
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

const email = ref('')
const password = ref('')
const errorMessage = ref('')

async function handleLogin() {
  errorMessage.value = ''

  try {
    await authStore.login(email.value, password.value)

    router.push('/')
  } catch (error) {
    errorMessage.value = error.message
  }
}
</script>