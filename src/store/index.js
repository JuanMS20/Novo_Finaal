import { createStore } from 'vuex'
import { productService } from '@/services/api'

export default createStore({
  state: {
    products: [],
    loading: false,
    error: null
  },
  mutations: {
    setProducts(state, products) {
      state.products = products
    },
    setLoading(state, status) {
      state.loading = status
    },
    setError(state, error) {
      state.error = error
    }
  },
  actions: {
    async fetchProducts({ commit }, filters = {}) {
      try {
        commit('setLoading', true)
        const products = await productService.getProducts(filters)
        commit('setProducts', products)
      } catch (error) {
        commit('setError', 'Error al cargar los productos')
      } finally {
        commit('setLoading', false)
      }
    },

    async searchProducts({ commit }, query) {
      try {
        commit('setLoading', true)
        const products = await productService.searchProducts(query)
        commit('setProducts', products)
      } catch (error) {
        commit('setError', 'Error al buscar productos')
      } finally {
        commit('setLoading', false)
      }
    }
  },
  getters: {
    allProducts: state => state.products,
    isLoading: state => state.loading,
    error: state => state.error
  }
}) 