import { useState } from 'react';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { StatusBadge } from '../../components/common/Badge';
import { AlertBanner } from '../../components/common/Alert';
import { adminApi } from '../../services/adminApi';
import { Loader2, X } from 'lucide-react';

export function AdminDashboardPage() {
  const [activeTab, setActiveTab] = useState('all');
  const [search, setSearch] = useState('');
  const [error, setError] = useState(null);

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['orders', activeTab, search],
    queryFn: () => adminApi.getOrders({ status: activeTab === 'all' ? '' : activeTab, search }),
    keepPreviousData: true,
  });

  const statusMutation = useMutation({
    mutationFn: ({ ticketId, status, catatan_revisi }) =>
      adminApi.updateStatus(ticketId, { status, catatan_revisi }),
    onSuccess: () => refetch(),
    onError: () => setError('Gagal update status'),
  });

  const resultMutation = useMutation({
    mutationFn: ({ ticketId, data }) => adminApi.updateResult(ticketId, data),
    onSuccess: () => refetch(),
    onError: () => setError('Gagal upload hasil'),
  });

  const orders = data?.orders || [];
  const counts = data?.counts || { all: 0, terkirim: 0, diproses: 0, selesai: 0 };

  const tabs = [
    { key: 'all', label: 'Semua' },
    { key: 'terkirim', label: 'Terkirim' },
    { key: 'diproses', label: 'Diproses' },
    { key: 'selesai', label: 'Selesai' },
  ];

  const handleStatusChange = (ticketId, newStatus, catatanRevisi = '') => {
    statusMutation.mutate({ ticketId, status: newStatus, catatan_revisi: catatanRevisi });
  };

  const handleResultUpload = (ticketId, resultLink) => {
    resultMutation.mutate({ ticketId, data: { result_link: resultLink } });
  };

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
      <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Tabel Antrean</h1>

      {error && <AlertBanner type="error" message={error} />}

      {/* Filter Tabs */}
      <div className="flex gap-6 border-b mb-6 overflow-x-auto">
        {tabs.map((tab) => (
          <button
            key={tab.key}
            onClick={() => setActiveTab(tab.key)}
            className={`pb-3 px-2 font-medium whitespace-nowrap ${
              activeTab === tab.key
                ? 'border-b-2 border-dstudio-gold text-dstudio-gold'
                : 'text-gray-500 hover:text-dstudio-gold'
            }`}
          >
            {tab.label} ({counts[tab.key] || 0})
          </button>
        ))}
      </div>

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
          <table className="w-full min-w-[1000px]">
            <thead className="bg-dstudio-dark text-white">
              <tr>
                <th className="px-4 py-3 text-left text-sm">Tiket ID</th>
                <th className="px-4 py-3 text-left text-sm">Nama</th>
                <th className="px-4 py-3 text-left text-sm">WA</th>
                <th className="px-4 py-3 text-left text-sm">Layanan</th>
                <th className="px-4 py-3 text-left text-sm">Total</th>
                <th className="px-4 py-3 text-left text-sm">Status</th>
                <th className="px-4 py-3 text-left text-sm">Tgl Masuk</th>
                <th className="px-4 py-3 text-left text-sm">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {orders.map((order) => (
                <tr key={order.kode_tiket} className="border-b hover:bg-gray-50">
                  <td className="px-4 py-3 font-mono font-bold text-dstudio-gold">
                    #{order.kode_tiket}
                  </td>
                  <td className="px-4 py-3">{order.nama_pelanggan}</td>
                  <td className="px-4 py-3">
                    <a
                      href={`https://wa.me/${order.no_wa}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-green-600 hover:underline"
                    >
                      {order.no_wa}
                    </a>
                  </td>
                  <td className="px-4 py-3">{order.layanan?.nama_layanan}</td>
                  <td className="px-4 py-3">{formatPrice(order.total_bayar)}</td>
                  <td className="px-4 py-3">
                    <StatusBadge status={order.status_pesanan} />
                  </td>
                  <td className="px-4 py-3">{formatDate(order.created_at)}</td>
                  <td className="px-4 py-3">
                    <OrderActions
                      order={order}
                      onStatusChange={handleStatusChange}
                      onResultUpload={handleResultUpload}
                      isLoading={statusMutation.isLoading || resultMutation.isLoading}
                    />
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

function OrderActions({ order, onStatusChange, onResultUpload, isLoading }) {
  const [selectedStatus, setSelectedStatus] = useState(order.status_pesanan);
  const [resultLink, setResultLink] = useState('');
  const [revisiNote, setRevisiNote] = useState('');

  const statusOptions = ['terkirim', 'diproses', 'selesai', 'revisi', 'dibatalkan'];

  return (
    <div className="space-y-2">
      {/* Status Update */}
      <div className="flex gap-2">
        <select
          value={selectedStatus}
          onChange={(e) => setSelectedStatus(e.target.value)}
          className="text-sm border border-gray-300 rounded px-2 py-1"
          disabled={isLoading}
        >
          {statusOptions.map((s) => (
            <option key={s} value={s}>
              {s}
            </option>
          ))}
        </select>
        <button
          onClick={() => onStatusChange(order.kode_tiket, selectedStatus)}
          disabled={isLoading || selectedStatus === order.status_pesanan}
          className="text-xs bg-dstudio-dark text-white px-3 py-1 rounded hover:bg-gray-800 disabled:opacity-50"
        >
          Update
        </button>
      </div>

      {/* Result Upload (only if status = selesai) */}
      {order.status_pesanan === 'selesai' && (
        <div className="flex gap-2">
          <input
            type="text"
            value={resultLink}
            onChange={(e) => setResultLink(e.target.value)}
            placeholder="Link hasil foto"
            className="text-sm border border-gray-300 rounded px-2 py-1 flex-1"
          />
          <button
            onClick={() => onResultUpload(order.kode_tiket, resultLink)}
            disabled={isLoading || !resultLink}
            className="text-xs bg-dstudio-gold text-dstudio-dark px-3 py-1 rounded hover:bg-yellow-500 disabled:opacity-50"
          >
            Upload
          </button>
        </div>
      )}

      {/* Revisi Note (only if status = diproses) */}
      {order.status_pesanan === 'diproses' && (
        <div className="flex gap-2">
          <input
            type="text"
            value={revisiNote}
            onChange={(e) => setRevisiNote(e.target.value)}
            placeholder="Catatan revisi"
            className="text-sm border border-gray-300 rounded px-2 py-1 flex-1"
          />
          <button
            onClick={() => {
              onStatusChange(order.kode_tiket, 'revisi', revisiNote);
              setRevisiNote('');
            }}
            disabled={isLoading || !revisiNote}
            className="text-xs bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 disabled:opacity-50"
          >
            Revisi
          </button>
        </div>
      )}
    </div>
  );
}
