<template>
    <div class="container">
      <div class="profile-card">
        <div class="header">
          <div class="header-content">
            <h1 class="title">Editar Producto</h1>
          </div>
        </div>
  
        <div class="content">
          <form @submit.prevent="handleSaveProduct" class="edit-form">
            <div>
              <label for="name">Nombre del Producto</label>
              <input id="name" v-model="editedProduct.name" class="input" />
            </div>
            <div>
              <label for="category">Categoría</label>
              <input id="category" v-model="editedProduct.category" class="input" />
            </div>
            <div>
              <label for="price">Precio</label>
              <input id="price" type="number" v-model="editedProduct.price" class="input" />
            </div>
            <div>
              <label for="description">Descripción</label>
              <textarea id="description" v-model="editedProduct.description" class="input"></textarea>
            </div>
            <button type="submit" class="save-button">Guardar Cambios</button>
          </form>
        </div>
  
        <div v-if="editSuccess" class="success-message">
          <p>Producto actualizado con éxito.</p>
        </div>
  
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue'
  
  const productId = 1 // Este ID es un ejemplo, en un escenario real sería dinámico
  
  const publishedItems = ref([
    { id: 1, name: 'Libro de Matemáticas', price: 25.99, category: 'Libros', description: 'Un libro de matemáticas de nivel universitario' },
    { id: 2, name: 'Laptop usada', price: 299.99, category: 'Electrónicos', description: 'Laptop en buen estado con algunos rasguños' },
    { id: 3, name: 'Apuntes de Historia', price: 5.00, category: 'Apuntes', description: 'Apuntes completos para el curso de Historia' },
  ])
  
  const editedProduct = reactive({ ...publishedItems.value.find(item => item.id === productId) })
  
  const editSuccess = ref(false)
  
  const handleSaveProduct = () => {
    // Actualizamos el producto en la lista
    const productIndex = publishedItems.value.findIndex(item => item.id === editedProduct.id)
    if (productIndex !== -1) {
      publishedItems.value[productIndex] = { ...editedProduct }
      editSuccess.value = true
      setTimeout(() => {
        editSuccess.value = false
      }, 3000)
      console.log('Producto actualizado:', editedProduct)
    }
  }
  </script>
  
  <style scoped>
  .container {
    min-height: 100vh;
    padding: 2rem;
    background-color: #f7fafc;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .profile-card {
    max-width: 800px;
    width: 100%;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .header {
    background: linear-gradient(to right, #4299e1, #667eea);
    padding: 1.5rem;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .title {
    color: #ffffff;
    font-size: 1.75rem;
    font-weight: bold;
  }
  
  .content {
    padding: 2rem;
  }
  
  .edit-form label {
    font-weight: 500;
    color: #4a5568;
  }
  
  .input {
    width: 100%;
    margin-top: 0.5rem;
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px solid #cbd5e0;
    transition: border-color 0.3s;
  }
  
  .input:focus {
    border-color: #4299e1;
  }
  
  .save-button {
    width: 100%;
    padding: 0.75rem;
    background-color: #4299e1;
    color: #ffffff;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .save-button:hover {
    background-color: #2b6cb0;
  }
  
  .success-message {
    padding: 1rem;
    background-color: #68d391;
    color: #ffffff;
    text-align: center;
    margin-top: 1rem;
    border-radius: 4px;
  }
  </style>
  