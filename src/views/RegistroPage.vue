<template>
  <div class="register-page">
    <div class="register-container">
      <h1>Registro</h1>
      
      <form @submit.prevent="handleRegister" class="register-form">
        <div class="form-group">
          <label>Nombre completo</label>
          <input 
            v-model="formData.fullName"
            type="text"
            required
            placeholder="Ingresa tu nombre completo"
          >
        </div>

        <div class="form-group">
          <label>Correo electrónico</label>
          <input 
            v-model="formData.email"
            type="email"
            required
            placeholder="Ingresa tu correo electrónico"
          >
        </div>

        <div class="form-group">
          <label>Contraseña</label>
          <input 
            v-model="formData.password"
            type="password"
            required
            placeholder="Ingresa tu contraseña"
          >
        </div>

        <div class="form-group">
          <label>Confirmar contraseña</label>
          <input 
            v-model="formData.confirmPassword"
            type="password"
            required
            placeholder="Confirma tu contraseña"
          >
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <button type="submit" :disabled="loading" class="register-button">
          {{ loading ? 'Registrando...' : 'Registrarse' }}
        </button>

        <p class="login-link">
          ¿Ya tienes una cuenta? 
          <router-link to="/login">Inicia sesión</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '../services/auth'

const router = useRouter()

const formData = ref({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: ''
})

const loading = ref(false)
const error = ref(null)

const validateForm = () => {
  // Validar nombre completo
  if (formData.value.fullName.trim().length < 3) {
    throw new Error('El nombre debe tener al menos 3 caracteres')
  }

  // Validar email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(formData.value.email)) {
    throw new Error('El correo electrónico no es válido')
  }

  // Validar contraseña
  if (formData.value.password.length < 6) {
    throw new Error('La contraseña debe tener al menos 6 caracteres')
  }

  // Validar confirmación de contraseña
  if (formData.value.password !== formData.value.confirmPassword) {
    throw new Error('Las contraseñas no coinciden')
  }
}

const handleRegister = async () => {
  try {
    error.value = null
    loading.value = true

    // Validar formulario
    validateForm()

    const userData = {
      fullName: formData.value.fullName,
      email: formData.value.email,
      password: formData.value.password
    }

    await authService.register(userData)
    router.push('/app/principal')
  } catch (err) {
    console.error('Error en registro:', err)
    error.value = err.response?.data?.message || err.message || 'Error durante el registro'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.register-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f3f4f6;
  padding: 2rem;
}

.register-container {
  background-color: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

h1 {
  text-align: center;
  color: #1f2937;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #4b5563;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.error-message {
  color: #dc2626;
  margin-bottom: 1rem;
  text-align: center;
}

.register-button {
  width: 100%;
  padding: 0.75rem;
  background-color: #2563eb;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s;
}

.register-button:hover:not(:disabled) {
  background-color: #1d4ed8;
}

.register-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.login-link {
  text-align: center;
  margin-top: 1rem;
  color: #6b7280;
}

.login-link a {
  color: #2563eb;
  text-decoration: none;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>
