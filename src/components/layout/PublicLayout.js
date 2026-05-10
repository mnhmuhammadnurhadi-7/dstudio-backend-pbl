import { Link } from 'react-router-dom';
import { Camera, Menu, X } from 'lucide-react';
import { useState } from 'react';

export function PublicLayout({ children }) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  return (
    <div className="min-h-screen bg-white">
      {/* Navbar */}
      <nav className="bg-dstudio-dark text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <Link to="/" className="flex items-center gap-2">
              <Camera className="w-6 h-6 text-dstudio-gold" />
              <span className="font-bold text-xl">DStudio</span>
            </Link>

            {/* Desktop Nav */}
            <div className="hidden md:flex items-center gap-8">
              <Link to="/" className="hover:text-dstudio-gold transition">Beranda</Link>
              <Link to="/layanan" className="hover:text-dstudio-gold transition">Layanan</Link>
              <Link to="/cek-status" className="hover:text-dstudio-gold transition">Cek Status</Link>
              <Link
                to="/pesan/step-1"
                className="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition"
              >
                Pesan Sekarang
              </Link>
            </div>

            {/* Mobile Menu Button */}
            <button
              className="md:hidden p-2"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
              {mobileMenuOpen ? <X /> : <Menu />}
            </button>
          </div>
        </div>

        {/* Mobile Nav */}
        {mobileMenuOpen && (
          <div className="md:hidden bg-gray-800 px-4 py-4 space-y-3">
            <Link to="/" className="block hover:text-dstudio-gold">Beranda</Link>
            <Link to="/layanan" className="block hover:text-dstudio-gold">Layanan</Link>
            <Link to="/cek-status" className="block hover:text-dstudio-gold">Cek Status</Link>
            <Link
              to="/pesan/step-1"
              className="block bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-semibold text-center"
            >
              Pesan Sekarang
            </Link>
          </div>
        )}
      </nav>

      {/* Main Content */}
      <main>{children}</main>

      {/* Footer */}
      <footer className="bg-dstudio-dark text-white py-8">
        <div className="max-w-7xl mx-auto px-4 text-center">
          <p className="text-gray-400 text-sm">
            &copy; {new Date().getFullYear()} DStudio Photography. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}
