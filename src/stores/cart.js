import { defineStore } from 'pinia'
import { computed, ref } from 'vue'

export const useCartStore = defineStore('cart', () => {
  const items = ref([])

  const totalItems = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const totalPrice = computed(() => {
    return items.value.reduce(
      (total, item) => total + item.price * item.quantity,
      0
    )
  })

  function addToCart(product) {
    const existingItem = items.value.find(
      (item) => item.id === product.id
    )

    if (existingItem) {
      existingItem.quantity++
      return
    }

    items.value.push({
      ...product,
      quantity: 1
    })
  }

  function removeFromCart(productId) {
    items.value = items.value.filter(
      (item) => item.id !== productId
    )
  }

  function clearCart() {
    items.value = []
  }

  return {
    items,
    totalItems,
    totalPrice,
    addToCart,
    removeFromCart,
    clearCart
  }
})