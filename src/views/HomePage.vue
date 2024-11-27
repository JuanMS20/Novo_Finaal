<template>
  <div class="landing-page">
    <nav class="navigation">
      <div class="nav-buttons">
        <router-link v-for="item in navItems" :key="item.href" :to="item.href">
          <button class="nav-button">
            {{ item.label }}
          </button>
        </router-link>
      </div>
    </nav>

    <main class="main-content">
      <div class="content-wrapper">
        <h1>Tu Mercado Estudiantil Digital</h1>
        <p class="subtitle">
          Conectamos personas para hacer el intercambio de materiales académicos mas facil y accesible
        </p>
        <router-link to="/app/principal">
          <button class="cta-button">
            Empezar
            <ArrowRight class="arrow-icon" />
          </button>
        </router-link>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ArrowRight } from 'lucide-vue-next'

const navItems = [
  { label: "REGISTRO", href: "/registro" },
  { label: "ENTRAR", href: "/login" },
]
</script>

<style scoped>
.landing-page {
  min-height: 100vh;
  background-image: url('@/assets/teenagers-with-textbooks-street.jpg');
  background-size: cover; 
  background-position: center; 
  background-repeat: no-repeat; 
  color: #1f2937;
}

.navigation {
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-buttons {
  display: flex;
  gap: 1rem;
}

.nav-button {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  background-color: rgba(255, 255, 255, 0.5);
  color: #4b5563;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s, color 0.3s;
}

.nav-button:hover {
  background-color: rgba(255, 255, 255, 0.8);
  color: #1f2937;
}

.nav-arrows {
  display: flex;
  flex-direction: column;
  color: #6b7280;
}

.arrow {
  width: 1rem;
  height: 1rem;
  cursor: pointer;
}

.main-content {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 80px);
}

.content-wrapper {
  text-align: center;
  padding: 2rem;
  background-color: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border-radius: 1.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  max-width: 48rem;
  width: 100%;
  margin: 0 1rem;
  transition: transform 0.3s;
}

.content-wrapper:hover {
  transform: scale(1.05);
}

h1 {
  font-size: 3rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #1f2937;
}

.subtitle {
  font-size: 1.25rem;
  margin-bottom: 2rem;
  color: #4b5563;
}

.cta-button {
  font-size: 1.125rem;
  padding: 1rem 2rem;
  background-color: #1f2937;
  color: white;
  border: none;
  border-radius: 9999px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s;
}

.cta-button:hover {
  background-color: #374151;
}

.arrow-icon {
  margin-left: 0.5rem;
  width: 1.25rem;
  height: 1.25rem;
}

@media (max-width: 640px) {
  .nav-buttons {
    flex-wrap: wrap;
    justify-content: center;
  }

  h1 {
    font-size: 2rem;
  }

  .subtitle {
    font-size: 1rem;
  }
}
</style>