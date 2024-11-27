<template>
  <div class="product-detail">
    <div class="container">
      <div v-if="loading" class="loading">
        Cargando producto...
      </div>

      <div v-else-if="error" class="error-message">
        {{ error }}
      </div>

      <div v-else class="product-content">
        <div class="product-images">
          <img :src="product.image_url || '/placeholder.jpg'" :alt="product.title" class="main-image">
        </div>

        <div class="product-info">
          <h1>{{ product.title }}</h1>
          
          <div class="price-condition">
            <span class="price">${{ product.price }}</span>
            <span class="condition">{{ product.condition_state }}</span>
          </div>

          <div class="description">
            <h2>Descripción</h2>
            <p>{{ product.description }}</p>
          </div>

          <div class="seller-info">
            <h2>Vendedor</h2>
            <div class="seller-details">
              <img :src="product.seller_image || '/user-placeholder.jpg'" alt="Vendedor" class="seller-image">
              <div>
                <p class="seller-name">{{ product.seller_name }}</p>
                <p class="seller-rating">Calificación: ⭐⭐⭐⭐⭐</p>
              </div>
            </div>
          </div>

          <div class="actions">
            <button @click="contactSeller" class="contact-button">
              Contactar Vendedor
            </button>
            <button @click="addToFavorites" class="favorite-button" :class="{ 'is-favorite': isFavorite }">
              {{ isFavorite ? '❤️ En Favoritos' : '🤍 Agregar a Favoritos' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { productService } from '@/services/api'

const route = useRoute()
const router = useRouter()
const product = ref(null)
const loading = ref(true)
const error = ref('')
const isFavorite = ref(false)

const loadProduct = async () => {
  const productId = route.params.id
  if (!productId) {
    router.push('/app/principal')
    return
  }

  try {
    loading.value = true
    error.value = ''
    const response = await productService.getProducts({ id: productId })
    product.value = Array.isArray(response) ? response[0] : response
  } catch (err) {
    error.value = 'Error al cargar el producto'
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

const contactSeller = () => {
  // Implementar lógica de contacto
  window.location.href = `mailto:${product.value.seller_email}?subject=Interés en: ${product.value.title}`
}

const addToFavorites = () => {
  isFavorite.value = !isFavorite.value
  // Implementar lógica de favoritos
}

onMounted(() => {
  loadProduct()
})
</script>

<style scoped>
.product-detail {
  min-height: 100vh;
  background-color: #f9fafb;
  padding: 2rem 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.product-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  background-color: white;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.product-images {
  position: relative;
  border-radius: 0.5rem;
  overflow: hidden;
}

.main-image {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 0.5rem;
}

.product-info {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

h1 {
  font-size: 2rem;
  color: #1f2937;
  margin-bottom: 1rem;
}

.price-condition {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.price {
  font-size: 2rem;
  font-weight: bold;
  color: #2563eb;
}

.condition {
  padding: 0.5rem 1rem;
  background-color: #e5e7eb;
  border-radius: 9999px;
  font-size: 0.875rem;
  color: #4b5563;
}

.description {
  margin-bottom: 2rem;
}

.description h2 {
  font-size: 1.25rem;
  color: #374151;
  margin-bottom: 0.5rem;
}

.description p {
  color: #6b7280;
  line-height: 1.6;
}

.seller-info {
  padding: 1.5rem;
  background-color: #f3f4f6;
  border-radius: 0.5rem;
  margin-bottom: 2rem;
}

.seller-info h2 {
  font-size: 1.25rem;
  color: #374151;
  margin-bottom: 1rem;
}

.seller-details {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.seller-image {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
}

.seller-name {
  font-weight: 500;
  color: #1f2937;
}

.seller-rating {
  color: #6b7280;
  font-size: 0.875rem;
}

.actions {
  display: flex;
  gap: 1rem;
  margin-top: auto;
}

.contact-button,
.favorite-button {
  flex: 1;
  padding: 1rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
}

.contact-button {
  background-color: #2563eb;
  color: white;
}

.contact-button:hover {
  background-color: #1d4ed8;
}

.favorite-button {
  background-color: #f3f4f6;
  color: #4b5563;
  border: 1px solid #e5e7eb;
}

.favorite-button:hover {
  background-color: #e5e7eb;
}

.favorite-button.is-favorite {
  background-color: #fecaca;
  color: #dc2626;
  border-color: #fca5a5;
}

.loading,
.error-message {
  text-align: center;
  padding: 4rem;
  font-size: 1.125rem;
}

.error-message {
  color: #dc2626;
}

@media (max-width: 768px) {
  .product-content {
    grid-template-columns: 1fr;
  }

  .main-image {
    height: 300px;
  }

  .actions {
    flex-direction: column;
  }
}
</style> 