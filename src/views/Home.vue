<template>
  <div class="home">
    <div v-if="!isLoggedIn" class="auth-container">
      <div class="auth-form">
        <h2>{{ isRegistering ? 'Registro' : 'Iniciar Sesión' }}</h2>
        <form @submit.prevent="handleSubmit">
          <div class="form-group">
            <label>Email:</label>
            <input type="email" v-model="email" required>
          </div>
          <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" v-model="password" required>
          </div>
          <button type="submit" class="btn-primary">
            {{ isRegistering ? 'Registrarse' : 'Iniciar Sesión' }}
          </button>
        </form>
        <p @click="isRegistering = !isRegistering" class="toggle-auth">
          {{ isRegistering ? '¿Ya tienes cuenta? Inicia sesión' : '¿No tienes cuenta? Regístrate' }}
        </p>
      </div>
    </div>

    <div v-else class="tasks-container">
      <div class="header">
        <h2>Mis Tareas</h2>
        <button @click="logout" class="btn-secondary">Cerrar Sesión</button>
      </div>

      <div class="new-task">
        <form @submit.prevent="createNewTask">
          <input type="text" v-model="newTask.title" placeholder="Título de la tarea" required>
          <input type="text" v-model="newTask.description" placeholder="Descripción (opcional)">
          <button type="submit" class="btn-primary">Agregar Tarea</button>
        </form>
      </div>

      <div class="tasks-list">
        <div v-for="task in tasks" :key="task.id" class="task-item">
          <div class="task-content">
            <h3>{{ task.title }}</h3>
            <p>{{ task.description }}</p>
            <small>Creada: {{ new Date(task.created_at).toLocaleDateString() }}</small>
          </div>
          <div class="task-actions">
            <button @click="toggleTaskComplete(task)" 
                    :class="['btn-status', task.completed ? 'completed' : '']">
              {{ task.completed ? 'Completada' : 'Pendiente' }}
            </button>
            <button @click="deleteTask(task.id)" class="btn-danger">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { authService, taskService } from '@/services/api';

export default {
  name: 'Home',
  data() {
    return {
      isLoggedIn: false,
      isRegistering: false,
      email: '',
      password: '',
      tasks: [],
      newTask: {
        title: '',
        description: ''
      }
    };
  },
  created() {
    this.checkAuth();
  },
  methods: {
    async checkAuth() {
      const token = localStorage.getItem('token');
      if (token) {
        this.isLoggedIn = true;
        await this.loadTasks();
      }
    },
    async handleSubmit() {
      try {
        if (this.isRegistering) {
          await authService.register(this.email, this.password);
        }
        await authService.login(this.email, this.password);
        this.isLoggedIn = true;
        this.loadTasks();
      } catch (error) {
        console.error('Error de autenticación:', error);
        alert('Error en la autenticación. Por favor, intenta de nuevo.');
      }
    },
    logout() {
      authService.logout();
      this.isLoggedIn = false;
      this.tasks = [];
    },
    async loadTasks() {
      try {
        this.tasks = await taskService.getTasks();
      } catch (error) {
        console.error('Error al cargar tareas:', error);
      }
    },
    async createNewTask() {
      try {
        await taskService.createTask(this.newTask);
        this.newTask.title = '';
        this.newTask.description = '';
        await this.loadTasks();
      } catch (error) {
        console.error('Error al crear tarea:', error);
      }
    },
    async toggleTaskComplete(task) {
      try {
        await taskService.updateTask(task.id, {
          ...task,
          completed: !task.completed
        });
        await this.loadTasks();
      } catch (error) {
        console.error('Error al actualizar tarea:', error);
      }
    },
    async deleteTask(taskId) {
      try {
        await taskService.deleteTask(taskId);
        await this.loadTasks();
      } catch (error) {
        console.error('Error al eliminar tarea:', error);
      }
    }
  }
};
</script>

<style scoped>
.home {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
}

.auth-form {
  background: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 400px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}

.btn-primary {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}

.btn-secondary {
  background-color: #666;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.toggle-auth {
  text-align: center;
  margin-top: 20px;
  color: #666;
  cursor: pointer;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.new-task {
  margin-bottom: 30px;
}

.task-item {
  background: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 15px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.task-content h3 {
  margin: 0 0 10px 0;
}

.task-content p {
  color: #666;
  margin: 0 0 5px 0;
}

.task-content small {
  color: #999;
}

.task-actions {
  display: flex;
  gap: 10px;
}

.btn-status {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  background-color: #ffc107;
  color: black;
}

.btn-status.completed {
  background-color: #28a745;
  color: white;
}
</style> 