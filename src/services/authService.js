/* eslint-disable no-unused-vars */
// Deshabilitamos temporalmente las advertencias de ESLint mientras desarrollamos

export const authService = {
  async login(email, password) {
    // Simulamos una llamada al backend
    return new Promise((resolve) => {
      setTimeout(() => {
        // En un caso real, aquí validaríamos con el backend
        const mockUser = {
          id: 1,
          email: email,
          name: "Usuario Demo"
        }
        localStorage.setItem('user', JSON.stringify(mockUser))
        resolve(mockUser)
      }, 1000)
    })
  },

  async register(email, password) {
    // Simulamos una llamada al backend
    return new Promise((resolve) => {
      setTimeout(() => {
        // En un caso real, aquí registraríamos en el backend
        const mockUser = {
          id: 1,
          email: email,
          name: "Usuario Demo"
        }
        localStorage.setItem('user', JSON.stringify(mockUser))
        resolve(mockUser)
      }, 1000)
    })
  },

  logout() {
    localStorage.removeItem('user')
  },

  getCurrentUser() {
    const userStr = localStorage.getItem('user')
    return userStr ? JSON.parse(userStr) : null
  },

  isAuthenticated() {
    return !!this.getCurrentUser()
  }
} 