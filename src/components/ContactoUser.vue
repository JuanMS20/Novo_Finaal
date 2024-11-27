<template>
    <div class="contact-container">
      <div class="contact-info">
        <!-- Icono de WhatsApp sin necesidad de imagen -->
        <i class="fab fa-whatsapp whatsapp-icon"></i>
        <p class="contact-number">{{ whatsappNumber }}</p>
      </div>
      <div class="upload-section">
        <label for="upload" class="upload-label">Subir comprobante:</label>
        <input type="file" id="upload" @change="handleFileUpload" class="file-input" />
        <button @click="uploadFile" class="upload-button">Subir</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  // Número de WhatsApp y archivo seleccionado
  const whatsappNumber = '+57 312 8210410'
  const selectedFile = ref(null)
  
  // Manejar el archivo seleccionado
  const handleFileUpload = (event) => {
    selectedFile.value = event.target.files[0]
  }
  
  // Función para manejar el envío del archivo
  const uploadFile = () => {
    if (!selectedFile.value) {
      alert('Por favor, selecciona un archivo antes de subirlo.')
      return
    }
    
    // Aquí puedes implementar la lógica para subir el archivo, por ejemplo, enviarlo a un servidor
    console.log('Archivo cargado:', selectedFile.value.name)
    alert('Archivo subido exitosamente')
  }
  </script>
  
  <style scoped>
  @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
  
  .contact-container {
    width: 300px;
    padding: 20px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    font-family: Arial, sans-serif;
  }
  
  .contact-info {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    padding: 10px;
    background-color: #25d366; /* Color verde de WhatsApp */
    border-radius: 25px;
  }
  
  .whatsapp-icon {
    font-size: 24px;
    color: white;
    margin-right: 10px;
  }
  
  .contact-number {
    font-size: 16px;
    color: white;
    font-weight: bold;
  }
  
  .upload-section {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  
  .upload-label {
    font-size: 14px;
    color: #495057;
    margin-bottom: 8px;
  }
  
  .file-input {
    margin-bottom: 10px;
  }
  
  .upload-button {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 14px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, box-shadow 0.3s;
  }
  
  .upload-button:hover {
    background-color: #218838;
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
  }
  
  .upload-button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.5);
  }
  </style>
  