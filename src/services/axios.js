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

  async post(url, data, config = {}) {
    try {
      const token = localStorage.getItem('token')
      const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...config.headers
      }

      console.log('Enviando petición POST a:', `${BASE_URL}${url}`)
      console.log('Datos:', data)
      console.log('Headers:', headers)

      const response = await fetch(`${BASE_URL}${url}`, {
        method: 'POST',
        headers,
        body: JSON.stringify(data),
        credentials: 'include'
      })

      const responseData = await response.json()
      console.log('Respuesta del servidor:', responseData)
      
      if (!response.ok) {
        throw {
          response: {
            data: responseData,
            status: response.status,
            statusText: response.statusText
          }
        }
      }

      return responseData
    } catch (error) {
      console.error('Error en la petición:', error)
      if (!error.response) {
        throw new Error('Error de conexión con el servidor')
      }
      throw error.response.data
    }
  }
}

export default axiosInstance 