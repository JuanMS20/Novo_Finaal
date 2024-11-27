<template>
  <nav class="navbar">
    <div class="container">
      <router-link to="/" class="logo">
        Novo
      </router-link>
      
      <!-- Resto de la navegación -->
      <div class="nav-links">
        <router-link to="/app/principal">Inicio</router-link>
        <router-link to="/app/productos">Productos</router-link>
        <router-link to="/app/crearproducto">Publicar</router-link>
        <!-- ... otros enlaces ... -->
      </div>
    </div>
  </nav>
</template>

<script setup>
// No se necesita lógica adicional
</script>

<style scoped>
.navbar {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2563eb;
  text-decoration: none;
  transition: color 0.3s ease;
}

.logo:hover {
  color: #1d4ed8;
}

.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-links a {
  color: #4b5563;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.nav-links a:hover {
  color: #2563eb;
}

.nav-links a.router-link-active {
  color: #2563eb;
}
</style>
