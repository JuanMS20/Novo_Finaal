<template>
  <div class="product-details">
    <div class="container">
      <div v-if="loading" class="loading">
        Cargando detalles del producto...
      </div>

      <div v-else-if="error" class="error">
        {{ error }}
      </div>

      <div v-else class="product-content">
        <div class="product-images">
          <img :src="product.image_url || '/placeholder.jpg'" :alt="product.title" class="main-image">
        </div>

        <div class="product-info">
          <h1>{{ product.title }}</h1>
          <div class="price-condition">
            <span class="price">${{ Number(product.price).toLocaleString() }}</span>
            <span class="condition">{{ product.condition_state }}</span>
          </div>

          <div class="description">
            <h2>Descripción</h2>
            <p>{{ product.description }}</p>
          </div>

          <div class="seller-info">
            <h2>Información del vendedor</h2>
            <p>{{ product.seller?.name || 'Vendedor no disponible' }}</p>
            <p>{{ product.seller?.email || 'Correo no disponible' }}</p>
          </div>

          <div class="actions">
            <button class="contact-button" @click="contactSeller">
              Contactar al vendedor
            </button>
            <button class="favorite-button" @click="toggleFavorite">
              {{ isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { productService } from '../services/api'

const route = useRoute()
const product = ref(null)
const loading = ref(true)
const error = ref(null)
const isFavorite = ref(false)

const loadProduct = async () => {
  loading.value = true
  error.value = null
  
  try {
    const result = await productService.getProductById(route.params.id)
    if (result) {
      product.value = result
    } else {
      error.value = "Producto no encontrado"
    }
  } catch (err) {
    error.value = "Error al cargar los detalles del producto"
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

const contactSeller = () => {
  // Implementar lógica para contactar al vendedor
  window.location.href = `mailto:${product.value.seller.email}`
}

const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
  // Implementar lógica para guardar en favoritos
}

onMounted(() => {
  loadProduct()
})
</script>

<style scoped>
.product-details {
  padding: 2rem 0;
  background-color: #f8f9fa;
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.product-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  background-color: white;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.main-image {
  width: 100%;
  height: auto;
  border-radius: 0.5rem;
  object-fit: cover;
}

.product-info h1 {
  font-size: 2rem;
  color: #2c3e50;
  margin-bottom: 1rem;
}

.price-condition {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
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

.description, .seller-info {
  margin-bottom: 2rem;
}

.description h2, .seller-info h2 {
  font-size: 1.25rem;
  color: #374151;
  margin-bottom: 0.5rem;
}

.actions {
  display: flex;
  gap: 1rem;
}

.contact-button, .favorite-button {
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.contact-button {
  background-color: #2563eb;
  color: white;
  border: none;
}

.favorite-button {
  background-color: white;
  color: #2563eb;
  border: 1px solid #2563eb;
}

.contact-button:hover {
  background-color: #1d4ed8;
}

.favorite-button:hover {
  background-color: #f8fafc;
}

.loading, .error {
  text-align: center;
  padding: 2rem;
  font-size: 1.25rem;
}

.error {
  color: #dc2626;
}

@media (max-width: 768px) {
  .product-content {
    grid-template-columns: 1fr;
  }
  
  .actions {
    flex-direction: column;
  }
}
</style>