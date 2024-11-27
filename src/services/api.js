/* eslint-disable no-unused-vars */
// Deshabilitamos temporalmente las advertencias de ESLint mientras desarrollamos

import axios from 'axios';

const API_URL = 'http://localhost:8090/api';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

// Interceptor para añadir el token a las peticiones
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export const authService = {
    async login(email, password) {
        const formData = new FormData();
        formData.append('username', email);
        formData.append('password', password);
        const response = await axios.post(`${API_URL}/token`, formData);
        if (response.data.access_token) {
            localStorage.setItem('token', response.data.access_token);
        }
        return response.data;
    },

    async register(email, password) {
        const response = await api.post('/users/', {
            email,
            password,
        });
        return response.data;
    },

    logout() {
        localStorage.removeItem('token');
    },
};

export const productService = {
    async getProducts(filters = {}) {
        let products = []
        try {
            products = [
                {
                    id: 1,
                    title: "Libro de Cálculo Avanzado",
                    description: "Texto completo para cursos de cálculo universitario. Incluye todos los temas del semestre y ejercicios resueltos.",
                    price: "75000",
                    image_url: "https://th.bing.com/th/id/OIP.YJg6c6rZqAzJTCBwZThSggHaKc?rs=1&pid=ImgDetMain",
                    condition_state: "Nuevo",
                    category: "libros",
                    seller: {
                        name: "Juan Pérez",
                        email: "juan.perez@email.com"
                    }
                },
                {
                    id: 2,
                    title: "Pack de Apuntes de Biología",
                    description: "Resúmenes detallados de biología celular y molecular. Material actualizado y organizado por temas.",
                    price: "30000",
                    image_url: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3",
                    condition_state: "Usado",
                    category: "notas",
                    seller: {
                        name: "María González",
                        email: "maria.g@email.com"
                    }
                },
                {
                    id: 3,
                    title: "Laptop para Estudiantes",
                    description: "Perfecta para tomar notas y hacer trabajos. Intel Core i5, 8GB RAM, 256GB SSD.",
                    price: "500000",
                    image_url: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853",
                    condition_state: "Semi-nuevo",
                    category: "electronicos",
                    seller: {
                        name: "Carlos Rodríguez",
                        email: "carlos.r@email.com"
                    }
                },
                {
                    id: 4,
                    title: "Kit de Dibujo Técnico",
                    description: "Incluye reglas, compás y transportador de precisión. Marca Staedtler, calidad profesional.",
                    price: "45000",
                    image_url: "https://th.bing.com/th/id/R.905c5cbca48cd19813f316e0c2936465",
                    condition_state: "Nuevo",
                    category: "suministros",
                    seller: {
                        name: "Ana Martínez",
                        email: "ana.m@email.com"
                    }
                },
                {
                    id: 5,
                    title: "Microscopio Digital",
                    description: "Ideal para prácticas de laboratorio en casa. Aumentos 40x-1000x, conexión USB.",
                    price: "200000",
                    image_url: "https://th.bing.com/th/id/OIP.1XbctlNd_Zpy5-KcZ-wbLgHaHa?rs=1&pid=ImgDetMain",
                    condition_state: "Nuevo",
                    category: "electronicos",
                    seller: {
                        name: "Pedro Sánchez",
                        email: "pedro.s@email.com"
                    }
                },
                {
                    id: 6,
                    title: "Calculadora Científica",
                    description: "Con funciones avanzadas para ingeniería y ciencias. Modelo Casio fx-991LA X.",
                    price: "80000",
                    image_url: "https://http2.mlstatic.com/calculadora-cientifica-casio-negro-fx-570ms-D_NQ_NP_333511-MLV20558458065_012016-F.jpg",
                    condition_state: "Nuevo",
                    category: "electronicos",
                    seller: {
                        name: "Laura Torres",
                        email: "laura.t@email.com"
                    }
                },
                {
                    id: 7,
                    title: "Mochila Universitaria",
                    description: "Espaciosa y resistente, perfecta para llevar libros y laptop. Marca Samsonite.",
                    price: "60000",
                    image_url: "https://th.bing.com/th/id/OIP.oEZK39hFZbnViselv129wgHaF2?rs=1&pid=ImgDetMain",
                    condition_state: "Nuevo",
                    category: "suministros",
                    seller: {
                        name: "Diego Morales",
                        email: "diego.m@email.com"
                    }
                },
                {
                    id: 8,
                    title: "Tableta Gráfica",
                    description: "Para estudiantes de diseño y arquitectura. Wacom Intuos, área activa 6x4 pulgadas.",
                    price: "150000",
                    image_url: "https://th.bing.com/th/id/OIP.C8oERVoc2NIde1XuAlDseQHaDy?rs=1&pid=ImgDetMain",
                    condition_state: "Semi-nuevo",
                    category: "suministros",
                    seller: {
                        name: "Sofía Vargas",
                        email: "sofia.v@email.com"
                    }
                }
            ]

            // Aplicar filtros
            if (filters) {
                // Filtrar por categoría
                if (filters.category) {
                    products = products.filter(product => product.category === filters.category)
                }

                // Filtrar por condición
                if (filters.condition) {
                    products = products.filter(product => product.condition_state === filters.condition)
                }

                // Filtrar por precio mínimo
                if (filters.minPrice) {
                    products = products.filter(product => 
                        parseInt(product.price) >= parseInt(filters.minPrice)
                    )
                }

                // Filtrar por precio máximo
                if (filters.maxPrice) {
                    products = products.filter(product => 
                        parseInt(product.price) <= parseInt(filters.maxPrice)
                    )
                }
            }

            return products
        } catch (error) {
            console.error('Error fetching products:', error)
            throw error
        }
    },

    async searchProducts(query) {
        const allProducts = await this.getProducts()
        return allProducts.filter(product => 
            product.title.toLowerCase().includes(query.toLowerCase()) ||
            product.description.toLowerCase().includes(query.toLowerCase())
        )
    },

    async getProductById(id) {
        const allProducts = await this.getProducts()
        return allProducts.find(product => product.id === parseInt(id))
    }
} 