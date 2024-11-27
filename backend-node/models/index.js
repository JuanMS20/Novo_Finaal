const { Sequelize } = require('sequelize');
const path = require('path');

const sequelize = new Sequelize({
  dialect: 'sqlite',
  storage: path.join(__dirname, '../database.sqlite')
});

const User = require('./user')(sequelize);
const Product = require('./product')(sequelize);

// Relaciones
User.hasMany(Product, { as: 'products', foreignKey: 'sellerId' });
Product.belongsTo(User, { as: 'seller', foreignKey: 'sellerId' });

module.exports = {
  sequelize,
  User,
  Product
}; 