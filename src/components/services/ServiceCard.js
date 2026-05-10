import { Link } from 'react-router-dom';

export function ServiceCard({ service }) {
  const { id_layanan, nama_layanan, deskripsi, harga } = service;

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price);
  };

  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
      <div className="bg-gray-200 h-48 flex items-center justify-center">
        <span className="text-gray-400">Gambar Layanan</span>
      </div>
      <div className="p-6">
        <h3 className="text-xl font-bold text-dstudio-dark mb-2">{nama_layanan}</h3>
        <p className="text-gray-600 text-sm mb-4 line-clamp-2">{deskripsi}</p>
        <p className="text-2xl font-bold text-dstudio-gold mb-4">{formatPrice(harga)}</p>
        <Link
          to="/pesan/step-1"
          className="block text-center bg-dstudio-dark text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition"
        >
          Pesan
        </Link>
      </div>
    </div>
  );
}
