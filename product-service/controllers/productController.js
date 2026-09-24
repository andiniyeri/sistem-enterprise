const productModel = require('../models/productModel');

// GET semua product
async function index(req, res) {
    try {
        const products = await productModel.getAllProducts();

        res.json({
            message: "Berhasil mengambil data product",
            data: products
        });

    } catch (error) {
        res.status(500).json({
            message: "Gagal mengambil data product",
            error: error.message
        });
    }
}

// GET product berdasarkan ID
async function show(req, res) {
    try {
        const { id } = req.params;

        const product = await productModel.getProductById(id);

        if (!product) {
            return res.status(404).json({
                message: "Product tidak ditemukan"
            });
        }

        res.json({
            message: "Berhasil mengambil data product",
            data: product
        });

    } catch (error) {
        res.status(500).json({
            message: "Gagal mengambil data product",
            error: error.message
        });
    }
}

// POST membuat product
async function store(req, res) {
    try {
        const { name, description, price, stock } = req.body;

        if (!name || price === undefined || stock === undefined) {
            return res.status(400).json({
                message: "name, price, dan stock wajib diisi"
            });
        }

        const product = await productModel.createProduct({
            name,
            description,
            price,
            stock
        });

        res.status(201).json({
            message: "Product berhasil dibuat",
            data: product
        });

    } catch (error) {
        res.status(500).json({
            message: "Gagal membuat product",
            error: error.message
        });
    }
}

// PUT update product
async function update(req, res) {
    try {
        const { id } = req.params;
        const { name, description, price, stock } = req.body;

        const existingProduct = await productModel.getProductById(id);

        if (!existingProduct) {
            return res.status(404).json({
                message: "Product tidak ditemukan"
            });
        }

        if (!name || price === undefined || stock === undefined) {
            return res.status(400).json({
                message: "name, price, dan stock wajib diisi"
            });
        }

        const product = await productModel.updateProduct(id, {
            name,
            description,
            price,
            stock
        });

        res.json({
            message: "Product berhasil diperbarui",
            data: product
        });

    } catch (error) {
        res.status(500).json({
            message: "Gagal memperbarui product",
            error: error.message
        });
    }
}

// DELETE product
async function destroy(req, res) {
    try {
        const { id } = req.params;

        const deleted = await productModel.deleteProduct(id);

        if (!deleted) {
            return res.status(404).json({
                message: "Product tidak ditemukan"
            });
        }

        res.json({
            message: "Product berhasil dihapus"
        });

    } catch (error) {
        res.status(500).json({
            message: "Gagal menghapus product",
            error: error.message
        });
    }
}

// Export semua controller
module.exports = {
    index,
    show,
    store,
    update,
    destroy
};