// Servicio de autenticación
import axios from '../utils/axios'

export const authService = {
  async register(userData) {
    try {
      const isConnected = await axios.checkConnection()
      if (!isConnected) {
        throw new Error('No se puede conectar al servidor')
      }

      const response = await axios.post('/auth/register', userData)
      if (response && response.token) {
        localStorage.setItem('token', response.token)
        return response
      }
      throw new Error('Respuesta inválida del servidor')
    } catch (error) {
      console.error('Error en registro:', error)
      throw new Error(error.message || 'Error durante el registro')
    }
  },

  async login(credentials) {
    try {
      const response = await axios.post('/auth/login', credentials)
      if (response && response.token) {
        localStorage.setItem('token', response.token)
        return response
      }
      throw new Error('Respuesta inválida del servidor')
    } catch (error) {
      console.error('Error en login:', error)
      throw new Error(error.message || 'Error durante el inicio de sesión')
    }
  },

  logout() {
    localStorage.removeItem('token')
    window.location.href = '/'
  },

  isAuthenticated() {
    return !!localStorage.getItem('token')
  },

  getToken() {
    return localStorage.getItem('token')
  }
} 