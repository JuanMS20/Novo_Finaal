<template>
  <div class="landing-layout">
    <nav class="navbar">
      <div class="nav-container">
        <router-link to="/" class="nav-brand">NovoMarket</router-link>
        <div class="nav-links">
          <router-link to="/login">Iniciar Sesión</router-link>
          <router-link to="/registro" class="btn-register">Registrarse</router-link>
        </div>
      </div>
    </nav>

    <main class="main-content">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
// No necesitamos nada aquí por ahora
</script>

<style scoped>
.landing-layout {
  min-height: 100vh;
  background-color: #ffffff;
}

.navbar {
  background-color: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 1rem 0;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-brand {
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
  text-decoration: none;
}

.nav-links {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.nav-links a {
  color: #2c3e50;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.nav-links a:hover {
  background-color: #f8f9fa;
}

.btn-register {
  background-color: #42b983;
  color: white !important;
}

.btn-register:hover {
  background-color: #3aa876 !important;
}

.main-content {
  padding-top: 5rem;
  max-width: 1200px;
  margin: 0 auto;
  padding-left: 2rem;
  padding-right: 2rem;
}

@media (max-width: 768px) {
  .nav-container {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }

  .nav-links {
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
  }
}
</style>