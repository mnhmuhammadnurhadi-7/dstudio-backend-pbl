import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { StatusBadge } from '../../components/common/Badge';
import { StaticRatingStars } from '../../components/common/RatingStars';
import { adminApi } from '../../services/adminApi';
import { X, ExternalLink } from 'lucide-react';

export function AdminCompletedPage() {
  const [search, setSearch] = useState('');

  const { data, isLoading } = useQuery({
    queryKey: ['completed-orders', search],
    queryFn: () => adminApi.getCompletedOrders({ search }),
  });

  const orders = data?.orders || [];

  const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    });
  };

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price || 0);
  };

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Pesanan Selesai</h1>

      {/* Search */}
      <div className="mb-6 relative">
        <input
          type="text"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Cari nama / kode tiket..."
          className="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold pr-10"
        />
        {search && (
          <button
            onClick={() => setSearch('')}
            className="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
          >
            <X className="w-5 h-5" />
          </button>
        )}
      </div>

      {/* Table */}
      <div className="bg-white rounded-lg shadow overflow-x-auto">
        {isLoading ? (
          <div className="p-8 text-center">Memuat...</div>
        ) : (
          <table className="w-full min-w-[900px]">
            <thead className="bg-dstudio-dark text-white">
              <tr>
                <th className="px-4 py-3 text-left text-sm">Tiket ID</th>
                <th className="px-4 py-3 text-left text-sm">Nama</th>
                <th className="px-4 py-3 text-left text-sm">Layanan</th>
                <th className="px-4 py-3 text-left text-sm">Total</th>
                <th className="px-4 py-3 text-left text-sm">Status</th>
                <th className="px-4 py-3 text-left text-sm">Rating</th>
                <th className="px-4 py-3 text-left text-sm">Admin</th>
                <th className="px-4 py-3 text-left text-sm">Tgl Selesai</th>
                <th className="px-4 py-3 text-left text-sm">Hasil</th>
              </tr>
            </thead>
            <tbody>
              {orders.map((order) => (
                <tr key={order.kode_tiket} className="border-b hover:bg-gray-50">
                  <td className="px-4 py-3 font-mono font-bold text-dstudio-gold">
                    #{order.kode_tiket}
                  </td>
                  <td className="px-4 py-3">{order.nama_pelanggan}</td>
                  <td className="px-4 py-3">{order.layanan?.nama_layanan}</td>
                  <td className="px-4 py-3">{formatPrice(order.total_bayar)}</td>
                  <td className="px-4 py-3">
                    <StatusBadge status={order.status_pesanan} />
                  </td>
                  <td className="px-4 py-3">
                    {order.rating ? (
                      <div>
                        <StaticRatingStars value={order.rating.nilai_rating} />
                        {order.rating.ulasan && (
                          <p className="text-xs text-gray-600 mt-1 max-w-xs truncate" title={order.rating.ulasan}>
                            {order.rating.ulasan}
                          </p>
                        )}
                      </div>
                    ) : (
                      '-'
                    )}
                  </td>
                  <td className="px-4 py-3">{order.adminUpdatedBy?.nama_admin || '-'}</td>
                  <td className="px-4 py-3">{formatDate(order.selesai_at)}</td>
                  <td className="px-4 py-3">
                    {order.link_foto_hasil ? (
                      <a
                        href={order.link_foto_hasil}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-dstudio-gold hover:underline inline-flex items-center gap-1"
                      >
                        <ExternalLink className="w-4 h-4" />
                        Lihat
                      </a>
                    ) : (
                      '-'
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </AdminLayout>
  );
}
