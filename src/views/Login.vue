<template>
  <main class="flex min-h-[70vh] items-center justify-center">
    <section class="w-full max-w-md">
      <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200">
        <div class="mb-8 text-center">
          <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            Connexion
          </h1>

          <p class="mt-2 text-gray-600">
            Connectez-vous à votre compte.
          </p>
        </div>

        <form
          class="space-y-5"
          @submit.prevent="handleLogin"
        >
          <div>
            <label
              for="email"
              class="mb-2 block text-sm font-medium text-gray-700"
            >
              Email
            </label>

            <input
              id="email"
              v-model="email"
              type="email"
              placeholder="Votre email"
              required
              class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            />
          </div>

          <div>
            <label
              for="password"
              class="mb-2 block text-sm font-medium text-gray-700"
            >
              Mot de passe
            </label>

            <input
              id="password"
              v-model="password"
              type="password"
              placeholder="Votre mot de passe"
              required
              class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            />
          </div>

          <p
            v-if="errorMessage"
            class="rounded-lg bg-red-50 p-3 text-sm font-medium text-red-700"
          >
            {{ errorMessage }}
          </p>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
          >
            {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
          Vous n'avez pas encore de compte ?
          <RouterLink
            to="/register"
            class="font-semibold text-indigo-600 hover:text-indigo-700"
          >
            Créer un compte
          </RouterLink>
        </p>
      </div>
    </section>
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