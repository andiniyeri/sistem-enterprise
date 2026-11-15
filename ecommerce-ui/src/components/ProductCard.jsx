// src/components/ProductCard.jsx
// Satu kartu produk: kotak gambar, nama, harga, dan rating.

import { Link } from 'react-router-dom'
import { formatRupiah } from '../utils/format'
import StarRating from './StarRating'

export default function ProductCard({ product }) {
  const { id, name, price, stock, image, rating } = product

  // Gambar contoh di database hanya 1 piksel (sangat pendek), jadi dianggap kosong.
  const hasImage = typeof image === 'string' && image.length > 200
  const src =
    image?.startsWith('http') || image?.startsWith('data:')
      ? image
      : `data:image/png;base64,${image}`

  return (
    <Link to={`/products/${id}`} className="card">
      <div className="card-image">
        {hasImage ? (
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