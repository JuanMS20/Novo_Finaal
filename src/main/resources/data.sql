-- Insertar usuario de prueba (contraseña: admin123)
INSERT INTO users (email, password, full_name, role) 
VALUES ('admin@novo.com', '$2a$10$8.UnVuG9HHgffUDAlk8qfOuVGkqRzgVymGe07xd00DMxs.AQubh4a', 'Admin', 'ADMIN');

-- Insertar algunos productos de prueba
INSERT INTO products (name, description, price, category, condition_state, user_id) 
VALUES 
('Libro de Matemáticas', 'Libro de cálculo diferencial', 29.99, 'Libros', 'NUEVO', 1),
('Laptop HP', 'Laptop usada en buen estado', 299.99, 'Electrónicos', 'USADO', 1),
('Apuntes de Historia', 'Apuntes completos del semestre', 9.99, 'Apuntes', 'NUEVO', 1); 