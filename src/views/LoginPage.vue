<template>
  <div class="login-container">
    <div class="login-card">
      <h2>Iniciar Sesión</h2>
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="formData.email" 
            required
          >
        </div>
        
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input 
            type="password" 
            id="password" 
            v-model="formData.password" 
            required
          >
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <button type="submit" :disabled="loading">
          {{ loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
        </button>
      </form>

      <div class="register-link">
        ¿No tienes una cuenta? 
        <router-link to="/registro">Regístrate</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '../services/auth'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const formData = ref({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    error.value = null
    loading.value = true
    
    const response = await authService.login({
      email: formData.value.email,
      password: formData.value.password
    })
    
    if (response) {
      router.push('/app/principal')
    }
  } catch (err) {
    console.error('Error en login:', err)
    error.value = err.message || 'Error durante el inicio de sesión'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f3f4f6;
  padding: 1rem;
}

.login-card {
  background-color: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

h2 {
  text-align: center;
  color: #1f2937;
  margin-bottom: 2rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  color: #4b5563;
  font-size: 0.875rem;
}

input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s;
}

input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

button {
  background-color: #2563eb;
  color: white;
  padding: 0.75rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #1d4ed8;
}

button:disabled {
  background-color: #93c5fd;
  cursor: not-allowed;
}

.error-message {
  color: #dc2626;
  font-size: 0.875rem;
  text-align: center;
}

.register-link {
  margin-top: 1.5rem;
  text-align: center;
  color: #4b5563;
  font-size: 0.875rem;
}

.register-link a {
  color: #2563eb;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>
  