const express = require('express');
const cors = require('cors');
const sqlite3 = require('sqlite3').verbose();
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const stripe = require('stripe')('sk_test_...');

const app = express();
const PORT = 8090;
const JWT_SECRET = 'NovoSecretKey2023';

// Middleware
app.use(cors({
  origin: 'http://localhost:8081',
  credentials: true
}));
app.use(express.json());

// Conexión a SQLite
const db = new sqlite3.Database('./database.sqlite', (err) => {
  if (err) {
    console.error('Error conectando a la base de datos:', err);
  } else {
    console.log('Conectado a la base de datos SQLite');
    initDatabase();
  }
});

// Inicializar base de datos
function initDatabase() {
  db.serialize(() => {
    // Tabla de usuarios
    db.run(`CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      email TEXT UNIQUE,
      password TEXT,
      full_name TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )`);

    // Tabla de productos
    db.run(`CREATE TABLE IF NOT EXISTS products (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT,
      description TEXT,
      price REAL,
      condition_state TEXT,
      user_id INTEGER,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users (id)
    )`);

    // Tabla de transacciones
    db.run(`CREATE TABLE IF NOT EXISTS transactions (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      product_id INTEGER,
      buyer_id INTEGER,
      seller_id INTEGER,
      amount REAL,
      status TEXT,
      stripe_payment_id TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (product_id) REFERENCES products (id),
      FOREIGN KEY (buyer_id) REFERENCES users (id),
      FOREIGN KEY (seller_id) REFERENCES users (id)
    )`);
  });
}

// Rutas
app.get('/api/health', (req, res) => {
  res.json({ status: 'OK', message: 'Servidor funcionando correctamente' });
});

// Registro de usuario
app.post('/api/auth/register', async (req, res) => {
  const { email, password, fullName } = req.body;

  try {
    // Verificar si el usuario ya existe
    db.get('SELECT id FROM users WHERE email = ?', [email], async (err, user) => {
      if (err) {
        return res.status(500).json({ message: 'Error en el servidor' });
      }
      if (user) {
        return res.status(400).json({ message: 'El email ya está registrado' });
      }

      // Encriptar contraseña
      const hashedPassword = await bcrypt.hash(password, 10);

      // Insertar nuevo usuario
      db.run(
        'INSERT INTO users (email, password, full_name) VALUES (?, ?, ?)',
        [email, hashedPassword, fullName],
        function(err) {
          if (err) {
            return res.status(500).json({ message: 'Error al crear usuario' });
          }

          const token = jwt.sign(
            { userId: this.lastID, email },
            JWT_SECRET,
            { expiresIn: '24h' }
          );

          res.status(201).json({
            message: 'Usuario registrado exitosamente',
            token,
            user: {
              id: this.lastID,
              email,
              fullName
            }
          });
        }
      );
    });
  } catch (error) {
    res.status(500).json({ message: 'Error en el servidor' });
  }
});

// Login
app.post('/api/auth/login', async (req, res) => {
  const { email, password } = req.body;

  try {
    db.get('SELECT * FROM users WHERE email = ?', [email], async (err, user) => {
      if (err) {
        return res.status(500).json({ message: 'Error en el servidor' });
      }
      if (!user) {
        return res.status(401).json({ message: 'Credenciales inválidas' });
      }

      const validPassword = await bcrypt.compare(password, user.password);
      if (!validPassword) {
        return res.status(401).json({ message: 'Credenciales inválidas' });
      }

      const token = jwt.sign(
        { userId: user.id, email: user.email },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      res.json({
        message: 'Login exitoso',
        token,
        user: {
          id: user.id,
          email: user.email,
          fullName: user.full_name
        }
      });
    });
  } catch (error) {
    res.status(500).json({ message: 'Error en el servidor' });
  }
});

// Productos
app.get('/api/products', (req, res) => {
  db.all('SELECT * FROM products ORDER BY created_at DESC', [], (err, products) => {
    if (err) {
      return res.status(500).json({ message: 'Error al obtener productos' });
    }
    res.json(products);
  });
});

app.post('/api/products', (req, res) => {
  const { name, description, price, conditionState, userId } = req.body;

  db.run(
    'INSERT INTO products (name, description, price, condition_state, user_id) VALUES (?, ?, ?, ?, ?)',
    [name, description, price, conditionState, userId],
    function(err) {
      if (err) {
        return res.status(500).json({ message: 'Error al crear producto' });
      }
      res.status(201).json({
        message: 'Producto creado exitosamente',
        productId: this.lastID
      });
    }
  );
});

// Nuevas rutas para pagos
app.post('/api/payment/create-intent', async (req, res) => {
  const { productId, buyerId } = req.body;

  try {
    // Obtener información del producto
    db.get('SELECT * FROM products WHERE id = ?', [productId], async (err, product) => {
      if (err || !product) {
        return res.status(404).json({ message: 'Producto no encontrado' });
      }

      // Crear intento de pago en Stripe
      const paymentIntent = await stripe.paymentIntents.create({
        amount: Math.round(product.price * 100), // Stripe usa centavos
        currency: 'mxn',
        metadata: {
          productId: productId,
          buyerId: buyerId,
          sellerId: product.user_id
        }
      });

      // Registrar la transacción en la base de datos
      db.run(
        'INSERT INTO transactions (product_id, buyer_id, seller_id, amount, status, stripe_payment_id) VALUES (?, ?, ?, ?, ?, ?)',
        [productId, buyerId, product.user_id, product.price, 'pending', paymentIntent.id]
      );

      res.json({
        clientSecret: paymentIntent.client_secret
      });
    });
  } catch (error) {
    console.error('Error al crear intento de pago:', error);
    res.status(500).json({ message: 'Error al procesar el pago' });
  }
});

// Webhook para actualizar el estado del pago
app.post('/api/payment/webhook', express.raw({type: 'application/json'}), async (req, res) => {
  const sig = req.headers['stripe-signature'];
  let event;

  try {
    event = stripe.webhooks.constructEvent(req.body, sig, 'whsec_...'); // Aquí va tu clave de webhook
  } catch (err) {
    return res.status(400).send(`Webhook Error: ${err.message}`);
  }

  if (event.type === 'payment_intent.succeeded') {
    const paymentIntent = event.data.object;
    
    // Actualizar el estado de la transacción
    db.run(
      'UPDATE transactions SET status = ? WHERE stripe_payment_id = ?',
      ['completed', paymentIntent.id]
    );

    // Aquí puedes agregar lógica adicional como enviar emails, etc.
  }

  res.json({received: true});
});

// Verificar estado del pago
app.get('/api/payment/status/:transactionId', (req, res) => {
  db.get(
    'SELECT * FROM transactions WHERE id = ?',
    [req.params.transactionId],
    (err, transaction) => {
      if (err) {
        return res.status(500).json({ message: 'Error al verificar el pago' });
      }
      if (!transaction) {
        return res.status(404).json({ message: 'Transacción no encontrada' });
      }
      res.json(transaction);
    }
  );
});

// Iniciar servidor
app.listen(PORT, () => {
  console.log(`Servidor corriendo en http://localhost:${PORT}`);
}); 