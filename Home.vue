<template>
  <div class="app">
    <main>
      <!-- Hero Section -->
      <section class="hero">
        <div class="container">
          <div class="hero-content">
            <h1 class="animated-text">Mercado Estudiantil</h1>
            <p class="fade-in">Compra y vende materiales estudiantiles de manera más fácil, encuentra los mejores descuentos para libros, apuntes, entre otros materiales.</p>
            <router-link to="/app/crearproducto">
              <button class="publish-button">Publica tu producto</button>
            </router-link>
          </div>
        </div>
      </section>

      <!-- Categories Section -->
      <section class="categories">
        <div class="container">
          <h2 class="section-title">Categorías</h2>
          <div class="category-grid">
            <div v-for="(category, index) in categories" :key="index" class="category-card">
              <component :is="category.icon" />
              <h3>{{ category.title }}</h3>
              <p>{{ category.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Products Section -->
      <section class="featured-products">
        <div class="container">
          <h2 class="section-title">Productos Destacados</h2>
          <div class="product-grid">
            <div v-if="featuredProducts.length === 0" class="no-products">
              No hay productos destacados disponibles
            </div>
            <div 
              v-else
              v-for="product in featuredProducts" 
              :key="product.id" 
              class="product-card hover-effect"
            >
              <div class="product-image">
                <img 
                  :src="product.image" 
                  :alt="product.name"
                  @error="handleImageError"
                />
              </div>
              <div class="product-info">
                <h3>{{ product.name }}</h3>
                <p>{{ product.description }}</p>
                <div class="product-footer">
                  <span class="price">{{ product.price }}</span>
                  <router-link :to="`/app/detallesproducto/${product.id}`">
                    <button class="view-details">Ver Detalles</button>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Latest Products Section -->
      <section class="latest-listings">
        <div class="container">
          <h2 class="section-title">Últimas Publicaciones</h2>
          <div class="product-grid">
            <div v-if="latestProducts.length === 0" class="no-products">
              No hay productos recientes disponibles
            </div>
            <div 
              v-else
              v-for="product in latestProducts" 
              :key="product.id" 
              class="product-card hover-effect"
            >
              <div class="product-image">
                <img 
                  :src="product.image" 
                  :alt="product.name"
                  @error="handleImageError"
                />
              </div>
              <div class="product-info">
                <h3>{{ product.name }}</h3>
                <p>{{ product.description }}</p>
                <div class="product-footer">
                  <span class="price">{{ product.price }}</span>
                  <router-link :to="`/app/detallesproducto/${product.id}`">
                    <button class="view-details">Ver Detalles</button>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <p>© 2024 Mercado Estudiantil. Todos los derechos reservados</p>
      <nav>
        <a href="#">Términos de Servicio</a>
        <a href="#">Privacidad</a>
      </nav>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { BookIcon, PencilIcon, LaptopIcon, CalculatorIcon } from 'lucide-vue-next';

const handleImageError = (event) => {
  event.target.src = '/placeholder-image.jpg'; // Asegúrate de tener esta imagen en tu carpeta public
};

const categories = ref([
  { icon: BookIcon, title: 'Libros', description: 'Encuentra y vende libros para todos los cursos' },
  { icon: PencilIcon, title: 'Apuntes', description: 'Comparte y ten acceso a apuntes de alta calidad' },
  { icon: LaptopIcon, title: 'Electrónica', description: 'Compra y vende calculadoras, laptops, etc.' },
  { icon: CalculatorIcon, title: 'Útiles', description: 'Abastécete de útiles escolares esenciales' }
]);

const featuredProducts = ref([]);
const latestProducts = ref([]);

const loadProducts = () => {
  try {
    // Cargamos los productos destacados
    featuredProducts.value = [
      { id: 1, name: "Libro de Cálculo Avanzado", description: "Texto completo para cursos de cálculo universitario", price: "$75.000", image: "https://th.bing.com/th/id/OIP.YJg6c6rZqAzJTCBwZThSggHaKc?rs=1&pid=ImgDetMain" },
      // ... resto de los productos destacados
    ];

    // Cargamos los últimos productos
    latestProducts.value = [
      { id: 9, name: "Libro de Física Cuántica", description: "Última edición con ejercicios resueltos", price: "$85.000", image: "https://images.unsplash.com/photo-1632571401005-458e9d244591?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80" },
      // ... resto de los últimos productos
    ];
  } catch (error) {
    console.error('Error al cargar los productos:', error);
  }
};

onMounted(() => {
  loadProducts();
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.product-card, .category-card').forEach(card => {
    observer.observe(card);
  });
});
</script>

<style scoped>
/* Mantén todos los estilos que ya tenías */
</style> 