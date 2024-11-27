<template>
  <div class="crear-producto">
    <h2>Crear Nuevo Producto</h2>
    <form @submit.prevent="handleSubmit" class="producto-form">
      <div class="form-group">
        <label>Título:</label>
        <input 
          v-model="producto.title" 
          type="text" 
          required 
          placeholder="Título del producto"
        >
      </div>

      <div class="form-group">
        <label>Descripción:</label>
        <textarea 
          v-model="producto.description" 
          required 
          placeholder="Descripción del producto"
        ></textarea>
      </div>

      <div class="form-group">
        <label>Precio:</label>
        <input 
          v-model="producto.price" 
          type="number" 
          required 
          min="0"
          step="1000"
        >
      </div>

      <div class="form-group">
        <label>Categoría:</label>
        <select v-model="producto.category" required>
          <option value="libros">Libros</option>
          <option value="electronicos">Electrónicos</option>
          <option value="notas">Notas y Apuntes</option>
          <option value="suministros">Suministros</option>
        </select>
      </div>

      <div class="form-group">
        <label>Estado:</label>
        <select v-model="producto.condition_state" required>
          <option value="Nuevo">Nuevo</option>
          <option value="Semi-nuevo">Semi-nuevo</option>
          <option value="Usado">Usado</option>
        </select>
      </div>

      <div class="form-group">
        <label>Imagen:</label>
        <input 
          type="file" 
          @change="handleImageChange" 
          accept="image/*"
          required
        >
      </div>

      <button type="submit" class="btn-submit" :disabled="isLoading">
        {{ isLoading ? 'Creando...' : 'Crear Producto' }}
      </button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'CrearProducto',
  setup() {
    const router = useRouter()
    const isLoading = ref(false)
    const producto = ref({
      title: '',
      description: '',
      price: '',
      category: '',
      condition_state: '',
      image_url: ''
    })

    const handleImageChange = (event) => {
      const file = event.target.files[0]
      if (file) {
        // Aquí puedes manejar la imagen si lo necesitas
        console.log('Imagen seleccionada:', file)
      }
    }

    const handleSubmit = async () => {
      try {
        isLoading.value = true
        // Aquí irá la lógica para enviar el producto al backend
        const response = await axios.post('http://localhost:8090/api/products', producto.value)
        console.log('Producto creado:', response.data)
        router.push('/') // Redirige a la página principal
      } catch (error) {
        console.error('Error al crear producto:', error)
        alert('Error al crear el producto. Por favor, intenta de nuevo.')
      } finally {
        isLoading.value = false
      }
    }

    return {
      producto,
      isLoading,
      handleImageChange,
      handleSubmit
    }
  }
}
</script>

<style scoped>
.crear-producto {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.producto-form {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input, textarea, select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

textarea {
  height: 100px;
  resize: vertical;
}

.btn-submit {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-submit:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>