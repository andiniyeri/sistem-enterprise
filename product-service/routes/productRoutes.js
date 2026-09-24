const express = require('express');

const router = express.Router();

const productController = require('../controllers/productController');

// GET semua product
router.get('/', productController.index);

// GET product berdasarkan ID
router.get('/:id', productController.show);

// POST membuat product
router.post('/', productController.store);

// PUT update product
router.put('/:id', productController.update);

// DELETE product
router.delete('/:id', productController.destroy);

module.exports = router;