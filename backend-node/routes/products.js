const express = require('express');
const { Product, User } = require('../models');
const jwt = require('jsonwebtoken');

const router = express.Router();
const JWT_SECRET = process.env.JWT_SECRET || 'tu_clave_secreta';

// Middleware de autenticación opcional
const optionalAuth = async (req, res, next) => {
  try {
    const token = req.headers.authorization?.split(' ')[1];
    if (token) {
      const decoded = jwt.verify(token, JWT_SECRET);
      req.user = decoded;
    }
    next();
  } catch (error) {
    next();
  }
};

// Obtener todos los productos
router.get('/', optionalAuth, async (req, res) => {
  try {
    const products = await Product.findAll({
      where: { isActive: true },
      include: [{
        model: User,
        as: 'seller',
        attributes: ['id', 'name', 'email']
      }]
    });
    res.json(products);
  } catch (error) {
    res.status(500).json({ message: 'Error al obtener productos', error: error.message });
  }
});

// Crear un nuevo producto
router.post('/', optionalAuth, async (req, res) => {
  try {
    if (!req.user) {
      return res.status(401).json({ message: 'No autorizado' });
    }

    const product = await Product.create({
      ...req.body,
      sellerId: req.user.userId
    });

    res.status(201).json(product);
  } catch (error) {
    res.status(500).json({ message: 'Error al crear producto', error: error.message });
  }
});

// Obtener un producto específico
router.get('/:id', optionalAuth, async (req, res) => {
  try {
    const product = await Product.findOne({
      where: { id: req.params.id, isActive: true },
      include: [{
        model: User,
        as: 'seller',
        attributes: ['id', 'name', 'email']
      }]
    });

    if (!product) {
      return res.status(404).json({ message: 'Producto no encontrado' });
    }

    res.json(product);
  } catch (error) {
    res.status(500).json({ message: 'Error al obtener producto', error: error.message });
  }
});

// Actualizar un producto
router.put('/:id', optionalAuth, async (req, res) => {
  try {
    if (!req.user) {
      return res.status(401).json({ message: 'No autorizado' });
    }

    const product = await Product.findOne({
      where: { id: req.params.id, sellerId: req.user.userId }
    });

    if (!product) {
      return res.status(404).json({ message: 'Producto no encontrado' });
    }

    await product.update(req.body);
    res.json(product);
  } catch (error) {
    res.status(500).json({ message: 'Error al actualizar producto', error: error.message });
  }
});

// Eliminar un producto (soft delete)
router.delete('/:id', optionalAuth, async (req, res) => {
  try {
    if (!req.user) {
      return res.status(401).json({ message: 'No autorizado' });
    }

    const product = await Product.findOne({
      where: { id: req.params.id, sellerId: req.user.userId }
    });

    if (!product) {
      return res.status(404).json({ message: 'Producto no encontrado' });
    }

    await product.update({ isActive: false });
    res.json({ message: 'Producto eliminado exitosamente' });
  } catch (error) {
    res.status(500).json({ message: 'Error al eliminar producto', error: error.message });
  }
});

module.exports = router; 