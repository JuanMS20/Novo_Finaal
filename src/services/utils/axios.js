// Configuración de axios y utilidades HTTP
const BASE_URL = 'http://localhost:8090/api'

const axiosInstance = {
  async checkConnection() {
    try {
      console.log('Verificando conexión con el servidor...')
      const response = await fetch(`${BASE_URL}/health`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      const isConnected = response.ok
      console.log('Conexión con el servidor:', isConnected ? 'OK' : 'Fallida')
      return isConnected
    } catch (error) {
      console.error('Error de conexión:', error)
      return false
    }
  },

  async get(url, config = {}) {
    await this.verifyConnection()
    try {
      const token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...config.headers
      }

      const response = await fetch(`${BASE_URL}${url}`, {
        method: 'GET',
        headers,
        credentials: 'include'
      })
      return await handleResponse(response)
    } catch (error) {
      throw handleError(error)
    }
  },

  async post(url, data, config = {}) {
    await this.verifyConnection()
    try {
      const token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...config.headers
      }

      const response = await fetch(`${BASE_URL}${url}`, {
        method: 'POST',
        headers,
        credentials: 'include',
        body: JSON.stringify(data)
      })
      return await handleResponse(response)
    } catch (error) {
      throw handleError(error)
    }
  },

  async verifyConnection() {
    const isConnected = await this.checkConnection()
    if (!isConnected) {
      throw new Error('No se puede establecer conexión con el servidor')
    }
  }
}

async function handleResponse(response) {
  let data
  try {
    data = await response.json()
  } catch (error) {
    data = null
  }
    
  if (!response.ok) {
    if (response.status === 401) {
      localStorage.removeItem('token')
      window.location.href = '/login'
    }
    throw { 
      response: { 
        data, 
        status: response.status,
        statusText: response.statusText 
      } 
    }
  }
    
  return data
}

function handleError(error) {
  console.error('Error en la petición:', error)
  if (!navigator.onLine) {
    return new Error('No hay conexión a internet')
  }
  if (error.name === 'AbortError') {
    return new Error('La petición fue cancelada')
  }
  if (error instanceof TypeError) {
    return new Error('Error de conexión con el servidor')
  }
  return error
}

export default axiosInstance 