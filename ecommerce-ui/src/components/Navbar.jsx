// src/components/Navbar.jsx
// Menu navigasi dan pemilih user. Belum ada login, jadi user dipilih manual.

import { NavLink } from 'react-router-dom'

const USERS = [1, 2, 3, 4, 5]

export default function Navbar({ userId, onUserChange }) {
  return (
    <header className="navbar">
      <NavLink to="/" className="logo">
        <span className="logo-icon">🛍</span>
        Toko Online
      </NavLink>

      <nav className="nav-links">
        <NavLink to="/" end>
          Produk
        </NavLink>
        <NavLink to="/cart">Keranjang</NavLink>
      </nav>

      <div className="nav-right">
        <label className="user">
          <select value={userId} onChange={(e) => onUserChange(Number(e.target.value))}>
            {USERS.map((id) => (
              <option key={id} value={id}>
                User {id}
              </option>
            ))}
          </select>
        </label>
        <NavLink to="/cart" className="btn-dark">
          🛒 Keranjang
        </NavLink>
      </div>
    </header>
  )
}