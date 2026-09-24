const pool = require('../config/db');

// GET semua product
async function getAllProducts() {
    const [rows] = await pool.query(
        'SELECT * FROM products ORDER BY created_at DESC'
    );

    return rows;
}

// GET product berdasarkan ID
async function getProductById(id) {
    const [rows] = await pool.query(
        'SELECT * FROM products WHERE id = ?',
        [id]
    );

    return rows[0];
}

// POST membuat product
async function createProduct(product) {
    const { name, description, price, stock } = product;

    const [result] = await pool.query(
        'INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)',
        [name, description, price, stock]
    );

    return getProductById(result.insertId);
}

// PUT update product
async function updateProduct(id, product) {
    const { name, description, price, stock } = product;

    await pool.query(
        'UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?',
        [name, description, price, stock, id]
    );

    return getProductById(id);
}

// DELETE product
async function deleteProduct(id) {
    const [result] = await pool.query(
        'DELETE FROM products WHERE id = ?',
        [id]
    );

    return result.affectedRows > 0;
}

module.exports = {
    getAllProducts,
    getProductById,
    createProduct,
    updateProduct,
    deleteProduct
};