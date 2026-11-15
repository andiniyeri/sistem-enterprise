// src/components/ProductCard.jsx
// Satu kartu produk: foto, nama, harga, stok, dan rating.

import { Link } from 'react-router-dom'
import { formatRupiah } from '../utils/format'
import StarRating from './StarRating'

// Foto produk disimpan di folder public/images, dipilih berdasarkan id produk.
const PHOTOS = {
  1: '/images/keyboard.jpeg',
  2: '/images/mouse.jpeg',
  3: '/images/headset.jpeg',
}

export default function ProductCard({ product }) {
  const { id, name, price, stock, image, rating } = product

  // Urutan pilihan: foto lokal, lalu gambar dari database (kalau panjang/asli), lalu huruf awal.
  let src = PHOTOS[id]
  if (!src && typeof image === 'string' && image.length > 200) {
    src =
      image.startsWith('http') || image.startsWith('data:')
        ? image
        : `data:image/png;base64,${image}`
  }

  return (
    <Link to={`/products/${id}`} className="card">
      <div className="card-image">
        {src ? (
          <img src={src} alt={name} />
        ) : (
          <span className="card-initial">{name.charAt(0)}</span>
        )}
        <span className="card-arrow">↗</span>
      </div>
      <div className="card-body">
        <p className="card-name">{name}</p>
        <p className="card-price">{formatRupiah(price)}</p>
        <p className="card-stock">Stok: {stock}</p>
        <div className="card-rating">
          {rating ? (
            <StarRating average={rating.average_rating} total={rating.total_reviews} />
          ) : (
            <span className="muted">Rating tidak tersedia</span>
          )}
        </div>
      </div>
    </Link>
  )
}