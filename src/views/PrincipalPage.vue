<template>
  <div class="app">
    <main>
      <!-- Hero Section -->
      <section class="hero">
        <div class="container">
          <div class="hero-content">
            <h1 class="animated-text">Mercado Estudiantil</h1>
            <p class="fade-in">Compra y vende materiales estudiantiles de manera más fácil, encuentra los mejores descuentos para libros, apuntes, entre otros materiales.</p>
            
            <!-- Barra de búsqueda -->
            <div class="search-container">
              <input 
                type="text" 
                v-model="searchQuery" 
                placeholder="Buscar productos..."
                @keyup.enter="handleSearch"
                class="search-input"
              >
              <button @click="handleSearch" class="search-button">
                <span class="search-icon">🔍</span>
              </button>
            </div>

            <router-link to="/app/crearproducto">
              <button class="publish-button">Publica tu producto</button>
            </router-link>
          </div>
        </div>
      </section>

      <!-- Filtros -->
      <section class="filters">
        <div class="container">
          <div class="filters-grid">
            <div class="filter-group">
              <label>Categoría</label>
              <select v-model="filters.category">
                <option value="">Todas</option>
                <option value="libros">Libros</option>
                <option value="notas">Notas y Apuntes</option>
                <option value="electronicos">Electrónicos</option>
                <option value="suministros">Suministros</option>
              </select>
            </div>

            <div class="filter-group">
              <label>Condición</label>
              <select v-model="filters.condition">
                <option value="">Todas</option>
                <option value="Nuevo">Nuevo</option>
                <option value="Semi-nuevo">Semi-nuevo</option>
                <option value="Usado">Usado</option>
              </select>
            </div>

            <div class="filter-group">
              <label>Precio Mínimo</label>
              <input 
                type="number" 
                v-model.number="filters.minPrice" 
                placeholder="Min"
                min="0"
                @input="validatePrices"
              >
            </div>

            <div class="filter-group">
              <label>Precio Máximo</label>
              <input 
                type="number" 
                v-model.number="filters.maxPrice" 
                placeholder="Max"
                min="0"
                @input="validatePrices"
              >
            </div>

            <button @click="applyFilters" class="filter-button">
              Aplicar Filtros
            </button>

            <button @click="resetFilters" class="reset-button">
              Resetear Filtros
            </button>
          </div>
        </div>
      </section>

      <!-- Productos -->
      <section class="products">
        <div class="container">
          <h2 class="section-title">Productos Disponibles</h2>
          
          <div v-if="loading" class="loading">
            Cargando productos...
          </div>

          <div v-else-if="error" class="error-message">
            {{ error }}
          </div>

          <div v-else-if="products.length === 0" class="no-products">
            No se encontraron productos
          </div>

          <div v-else class="products-grid">
            <div v-for="product in products" :key="product.id" class="product-card">
              <img :src="product.image_url || '/placeholder.jpg'" :alt="product.title" class="product-image">
              <div class="product-info">
                <h3>{{ product.title }}</h3>
                <p class="product-description">{{ product.description }}</p>
                <div class="product-details">
                  <span class="price">${{ Number(product.price).toLocaleString() }}</span>
                  <span class="condition">{{ product.condition_state }}</span>
                </div>
                <router-link :to="{ name: 'DetallesProducto', params: { id: product.id }}">
                  <button class="view-details">Ver Detalles</button>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { productService } from '@/services/api'

const loading = ref(false)
const error = ref('')
const products = ref([])
const searchQuery = ref('')
const filters = ref({
  category: '',
  condition: '',
  minPrice: '',
  maxPrice: ''
})

const validatePrices = () => {
  // Convertir a números
  filters.value.minPrice = filters.value.minPrice ? parseInt(filters.value.minPrice) : ''
  filters.value.maxPrice = filters.value.maxPrice ? parseInt(filters.value.maxPrice) : ''

  // Validar que el precio mínimo no sea mayor que el máximo
  if (filters.value.minPrice && filters.value.maxPrice) {
    if (filters.value.minPrice > filters.value.maxPrice) {
      filters.value.maxPrice = filters.value.minPrice
    }
  }

  // Asegurar que los precios no sean negativos
  if (filters.value.minPrice < 0) filters.value.minPrice = 0
  if (filters.value.maxPrice < 0) filters.value.maxPrice = 0
}

const resetFilters = () => {
  filters.value = {
    category: '',
    condition: '',
    minPrice: '',
    maxPrice: ''
  }
  loadProducts()
}

const applyFilters = () => {
  validatePrices()
  loadProducts()
}

const loadProducts = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await productService.getProducts(filters.value)
    products.value = response
  } catch (err) {
    error.value = 'Error al cargar los productos'
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

const handleSearch = async () => {
  if (!searchQuery.value.trim()) {
    return loadProducts()
  }
  
  loading.value = true
  error.value = ''
  
  try {
    const response = await productService.searchProducts(searchQuery.value)
    products.value = response
  } catch (err) {
    error.value = 'Error en la búsqueda'
    console.error('Error:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadProducts()
})
</script>

<style scoped>
.app {
  min-height: 100vh;
  background-color: #f9fafb;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Hero Section */
.hero {
  background-color: #1e40af;
  padding: 60px 0;
  color: white;
  text-align: center;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
}

.animated-text {
  font-size: 3rem;
  margin-bottom: 1rem;
  animation: fadeIn 1s ease-out;
}

.search-container {
  display: flex;
  max-width: 600px;
  margin: 2rem auto;
}

.search-input {
  flex: 1;
  padding: 1rem;
  border: none;
  border-radius: 8px 0 0 8px;
  font-size: 1rem;
}

.search-button {
  padding: 1rem 2rem;
  background-color: #2563eb;
  border: none;
  border-radius: 0 8px 8px 0;
  cursor: pointer;
  transition: background-color 0.3s;
}

.search-button:hover {
  background-color: #1d4ed8;
}

.publish-button {
  background-color: #10b981;
  color: white;
  padding: 1rem 2rem;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.publish-button:hover {
  background-color: #059669;
}

/* Filters Section */
.filters {
  background-color: white;
  padding: 2rem 0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  align-items: end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-group label {
  font-size: 0.875rem;
  color: #4b5563;
}

.filter-group select,
.filter-group input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.filter-button {
  padding: 0.75rem;
  background-color: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.filter-button:hover {
  background-color: #1d4ed8;
}

.reset-button {
  padding: 0.75rem;
  background-color: #64748b;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.reset-button:hover {
  background-color: #475569;
}

.filter-group input[type="number"] {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.filter-group input[type="number"]:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

/* Eliminar flechas de los inputs numéricos */
.filter-group input[type="number"]::-webkit-inner-spin-button,
.filter-group input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.filter-group input[type="number"] {
  -moz-appearance: textfield;
}

/* Products Section */
.products {
  padding: 3rem 0;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.product-card {
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s;
}

.product-card:hover {
  transform: translateY(-4px);
}

.product-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-info {
  padding: 1.5rem;
}

.product-info h3 {
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
  color: #1f2937;
}

.product-description {
  color: #6b7280;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-details {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.price {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2563eb;
}

.condition {
  padding: 0.25rem 0.75rem;
  background-color: #e5e7eb;
  border-radius: 9999px;
  font-size: 0.875rem;
  color: #4b5563;
}

.view-details {
  width: 100%;
  padding: 0.75rem;
  background-color: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.view-details:hover {
  background-color: #1d4ed8;
}

.loading,
.error-message,
.no-products {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}

.error-message {
  color: #dc2626;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>