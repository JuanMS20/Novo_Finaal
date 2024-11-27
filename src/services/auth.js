import axios from './axios'

export const authService = {
  async register(userData) {
    try {
      const isConnected = await axios.checkConnection()
      if (!isConnected) {
        throw new Error('No se puede conectar al servidor')
      }

      const response = await axios.post('/auth/register', userData)
      if (!response) {
        throw new Error('Respuesta inválida del servidor')
      }
      
      if (response.token) {
        localStorage.setItem('token', response.token)
      }
      return response
    } catch (error) {
      console.error('Error en registro:', error)
      throw error
    }
  },

  async login(credentials) {
    try {
      const response = await axios.post('/auth/login', credentials)
      if (!response) {
        throw new Error('Respuesta inválida del servidor')
      }
      
      if (response.token) {
        localStorage.setItem('token', response.token)
      }
      return response
    } catch (error) {
      console.error('Error en login:', error)
      throw error
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