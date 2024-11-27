<template>
  <div class="products-page">
    <h1>Productos</h1>
    
    <!-- Filtros -->
    <div class="filters">
      <div class="filter-group">
        <h3>Categorías</h3>
        <div class="filter-options">
          <label v-for="category in categories" :key="category.id">
            <input 
              type="checkbox" 
              :value="category.value"
              v-model="selectedCategories"
              @change="applyFilters"
            >
            {{ category.label }}
          </label>
        </div>
      </div>

      <div class="filter-group">
        <h3>Precio</h3>
        <div class="price-inputs">
          <input 
            type="number" 
            v-model="priceRange.min" 
            placeholder="Min"
            @change="applyFilters"
          >
          <span>-</span>
          <input 
            type="number" 
            v-model="priceRange.max" 
            placeholder="Max"
            @change="applyFilters"
          >
        </div>
      </div>
    </div>

    <!-- Lista de productos -->
    <div class="products-grid">
      <div v-if="loading" class="loading">
        Cargando productos...
      </div>
      <div v-else-if="products.length === 0" class="no-products">
        No se encontraron productos con los filtros seleccionados.
      </div>
      <div v-else class="product-card" v-for="product in products" :key="product.id">
        <img :src="product.image_url || '/placeholder.png'" :alt="product.title">
        <div class="product-info">
          <h3>{{ product.title }}</h3>
          <p class="price">${{ product.price }}</p>
          <p class="category">{{ product.category }}</p>
          <router-link :to="{ path: '/app/detallesproducto', query: { id: product.id }}" class="view-details">
            Ver detalles
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { productService } from '@/services/api'

const route = useRoute()
const loading = ref(false)
const products = ref([])
const selectedCategories = ref([])
const priceRange = ref({ min: '', max: '' })

const categories = [
  { id: 1, label: 'Libros', value: 'libros' },
  { id: 2, label: 'Notas', value: 'notas' },
  { id: 3, label: 'Electronicos', value: 'electronicos' },
  { id: 4, label: 'Suministros', value: 'suministros' }
]

// Cargar productos con filtros
const loadProducts = async () => {
  loading.value = true
  try {
    const filters = {
      categories: selectedCategories.value,
      minPrice: priceRange.value.min || undefined,
      maxPrice: priceRange.value.max || undefined
    }
    const response = await productService.getFilteredProducts(filters)
    products.value = response
  } catch (error) {
    console.error('Error al cargar productos:', error)
  } finally {
    loading.value = false
  }
}

// Aplicar filtros
const applyFilters = () => {
  loadProducts()
}

// Observar cambios en la ruta para actualizar filtros
watch(() => route.query, (newQuery) => {
  if (newQuery.category && !selectedCategories.value.includes(newQuery.category)) {
    selectedCategories.value = [newQuery.category]
    loadProducts()
  }
}, { immediate: true })

onMounted(() => {
  // Si hay una categoría en la URL, seleccionarla
  if (route.query.category) {
    selectedCategories.value = [route.query.category]
  }
  loadProducts()
})
</script>

<style scoped>
.products-page {
  padding: 2rem;
}

.filters {
  display: flex;
  gap: 2rem;
  margin-bottom: 2rem;
  padding: 1rem;
  background-color: #f9fafb;
  border-radius: 0.5rem;
}

.filter-group {
  flex: 1;
}

.filter-group h3 {
  margin-bottom: 0.5rem;
  font-size: 1rem;
  color: #374151;
}

.filter-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-options label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.price-inputs {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.price-inputs input {
  width: 100px;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 2rem;
  padding: 1rem;
}

.product-card {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.product-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-info {
  padding: 1rem;
}

.product-info h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #1f2937;
}

.price {
  font-size: 1.25rem;
  font-weight: bold;
  color: #059669;
  margin: 0.5rem 0;
}

.category {
  color: #6b7280;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.view-details {
  display: inline-block;
  padding: 0.5rem 1rem;
  background-color: #1f2937;
  color: white;
  text-decoration: none;
  border-radius: 0.25rem;
  transition: background-color 0.2s;
}

.view-details:hover {
  background-color: #374151;
}

.loading, .no-products {
  grid-column: 1 / -1;
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}
</style>