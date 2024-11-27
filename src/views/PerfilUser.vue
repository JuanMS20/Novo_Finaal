<template>
  <div class="container">
    <div class="profile-card">
      <div class="header">
        <div class="header-content">
          <h1 class="title">Perfil de Usuario</h1>
          <div class="actions">
            <button @click="handleEditProfile" class="edit-button">
              <PencilIcon class="icon" />
            </button>
            <button @click="handleLogout" class="logout-button">
              <LogOutIcon class="icon" />
            </button>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="profile-info">
          <img src="@/assets/hombre.jpg" :alt="user.name" class="avatar" />
          <div v-if="!isEditing" class="user-details">
            <h2 class="username">{{ user.name }}</h2>
            <p class="email">{{ user.email }}</p>
          </div>
          <form v-else @submit.prevent="handleSaveProfile" class="edit-form">
            <div>
              <label for="name">Nombre</label>
              <input id="name" v-model="editedUser.name" class="input" />
            </div>
            <div>
              <label for="email">Email</label>
              <input id="email" type="email" v-model="editedUser.email" class="input" />
            </div>
            <button type="submit" class="save-button">Guardar Cambios</button>
          </form>
        </div>

        <div class="published-items">
          <h3>Productos Publicados</h3>
          <div class="item-list">
            <div v-for="item in publishedItems" :key="item.id" class="item">
              <div>
                <h4 class="item-name">{{ item.name }}</h4>
                <p class="item-category">{{ item.category }}</p>
                <p class="item-price">${{ item.price.toFixed(2) }}</p>
              </div>
              <div class="item-actions">
                <button @click="editProduct(item)" class="edit-product-button">
                  <PencilIcon class="icon" />
                </button>
                <button @click="deleteProduct(item.id)" class="delete-product-button">
                  <TrashIcon class="icon" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { PencilIcon, LogOutIcon, TrashIcon } from 'lucide-vue-next'

// Create a router instance for navigation
const router = useRouter()

const isEditing = ref(false)
const user = reactive({
  name: 'John Doe',
  email: 'john@example.com',
  avatar: '/placeholder.svg?height=100&width=100'
})
const editedUser = reactive({ ...user })

const publishedItems = ref([
  { id: 1, name: 'Libro de Matemáticas', price: 25.99, category: 'Libros' },
  { id: 2, name: 'Laptop usada', price: 299.99, category: 'Electrónicos' },
  { id: 3, name: 'Apuntes de Historia', price: 5.00, category: 'Apuntes' },
])

const handleEditProfile = () => {
  isEditing.value = !isEditing.value
  if (isEditing.value) {
    Object.assign(editedUser, user)
  }
}

const handleSaveProfile = () => {
  Object.assign(user, editedUser)
  isEditing.value = false
  console.log('Perfil actualizado:', user)
}

const handleLogout = () => {
  console.log('Usuario cerró sesión')
}

const editProduct = (item) => {
  // Pasar el id del producto como parámetro a la ruta 'EditarP'
  router.push({ name: 'EditarP', params: { id: item.id } })
}

const deleteProduct = (id) => {
  publishedItems.value = publishedItems.value.filter(item => item.id !== id)
  console.log('Producto eliminado:', id)
}
</script>

<style>
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

.actions button {
  padding: 0.5rem;
  margin-left: 0.5rem;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.3s;
}

.edit-button {
  background-color: #ffffff;
  color: #4299e1;
}

.logout-button {
  background-color: #ffffff;
  color: #e53e3e;
}

.actions button:hover {
  transform: scale(1.1);
}

.content {
  padding: 2rem;
}

.profile-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 2rem;
}

.avatar {
  height: 96px;
  width: 96px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #bee3f8;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.user-details {
  text-align: center;
}

.username {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2d3748;
}

.email {
  color: #718096;
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

.published-items h3 {
  font-size: 1.25rem;
  font-weight: bold;
  color: #2d3748;
  border-bottom: 2px solid #e2e8f0;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem;
}

.item-list .item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  transition: box-shadow 0.3s;
}

.item:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.item-name {
  font-weight: 600;
  color: #2d3748;
}

.item-category {
  color: #718096;
}

.item-price {
  font-weight: bold;
  color: #4299e1;
}

.item-actions button {
  margin-left: 0.5rem;
  padding: 0.5rem;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.3s;
}

.edit-product-button {
  background-color: #d69e2e;
  color: #ffffff;
}

.delete-product-button {
  background-color: #e53e3e;
  color: #ffffff;
}

.item-actions button:hover {
  transform: scale(1.1);
}
</style>
