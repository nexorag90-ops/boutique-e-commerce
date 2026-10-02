import { defineStore } from 'pinia'
import { computed, ref, watch } from 'vue'

export const useCartStore = defineStore('cart', () => {
  const savedCart = localStorage.getItem('cart')

  const items = ref(savedCart ? JSON.parse(savedCart) : [])

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

  function increaseQuantity(productId) {
    const item = items.value.find((item) => item.id === productId)

    if (item) {
      item.quantity++
    }
  }

  function decreaseQuantity(productId) {
    const item = items.value.find((item) => item.id === productId)

    if (item && item.quantity > 1) {
      item.quantity--
    }
  }

  function removeFromCart(productId) {
    items.value = items.value.filter(
      (item) => item.id !== productId
    )
  }

  function clearCart() {
    items.value = []
  }

  watch(
    items,
    (newItems) => {
      localStorage.setItem('cart', JSON.stringify(newItems))
    },
    { deep: true }
  )

  return {
    items,
    totalItems,
    totalPrice,
    addToCart,
    increaseQuantity,
    decreaseQuantity,
    removeFromCart,
    clearCart
  }
})