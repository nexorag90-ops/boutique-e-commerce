<template>
  <main class="mx-auto max-w-7xl">
    <section
      class="overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 px-6 py-16 text-white shadow-xl sm:px-10 lg:px-16"
    >
      <div class="max-w-3xl">
        <span
          class="inline-flex rounded-full bg-white/15 px-4 py-2 text-sm font-medium ring-1 ring-white/20"
        >
          Bienvenue dans notre boutique
        </span>

        <h1
          class="mt-6 text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl"
        >
          Découvrez nos produits
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-8 text-indigo-100">
          Découvrez notre sélection de produits et trouvez facilement
          ce qui vous convient.
        </p>

        <div class="mt-8">
          <RouterLink
            to="/products"
            class="inline-flex items-center rounded-xl bg-white px-6 py-3 font-semibold text-indigo-700 shadow-sm transition hover:bg-gray-100"
          >
            Découvrir les produits
          </RouterLink>
        </div>
      </div>
    </section>
  </main>
</template>