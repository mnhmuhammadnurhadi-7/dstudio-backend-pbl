import { useState, useEffect } from 'react';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { StatusBadge } from '../../components/common/Badge';
import { AlertBanner } from '../../components/common/Alert';
import { adminApi } from '../../services/adminApi';
import { Loader2, X, Trash2 } from 'lucide-react';

export function AdminDashboardPage() {
  const [activeTab, setActiveTab] = useState('all');
  const [search, setSearch] = useState('');
  const [error, setError] = useState(null);

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['orders', activeTab, search],
    queryFn: () => adminApi.getOrders({ status: activeTab === 'all' ? '' : activeTab, search }),
  });

  const statusUpdateMutation = useMutation({
    mutationFn: ({ ticketId, requestBody }) => adminApi.updateOrderStatus(ticketId, requestBody),
    onSuccess: () => {
      alert('Status pesanan berhasil diperbarui!');
      refetch();
    },
    onError: (err) => {
      const errorMessage = err.response?.data?.message || 'Gagal update status';
      setError(errorMessage);
    },
  });

  const deleteMutation = useMutation({
    mutationFn: (ticketId) => adminApi.deleteOrder(ticketId),
    onSuccess: () => {
      alert('Pesanan berhasil dihapus!');
      refetch();
    },
    onError: (err) => {
      setError('Gagal menghapus pesanan');
    },
  });

  const orders = data?.orders || [];
  const counts = data?.counts || { all: 0, terkirim: 0, diproses: 0, selesai: 0, revisi: 0 };

  const tabs = [
    { key: 'all', label: 'Semua' },
    { key: 'terkirim', label: 'Terkirim' },
    { key: 'diproses', label: 'Diproses' },
    { key: 'selesai', label: 'Selesai' },
    { key: 'revisi', label: 'Revisi' },
  ];

  const handleStatusUpdate = (ticketId, requestBody) => {
    // Menggunakan mutation baru untuk endpoint PUT /api/admin/pesanan/{kode}/status
    statusUpdateMutation.mutate({ ticketId, requestBody });
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
            className={`pb-3 px-2 font-medium whitespace-nowrap ${activeTab === tab.key
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
          <table className="w-full min-w-[1600px]">
            <thead className="bg-dstudio-dark text-white">
              <tr>
                <th className="px-4 py-3 text-left text-sm">Tiket ID</th>
                <th className="px-4 py-3 text-left text-sm">Nama</th>
                <th className="px-4 py-3 text-left text-sm">No WA</th>
                <th className="px-4 py-3 text-left text-sm">Layanan</th>
                <th className="px-4 py-3 text-left text-sm">Link Foto [Client]</th>
                <th className="px-4 py-3 text-left text-sm">Total Bayar</th>
                <th className="px-4 py-3 text-left text-sm">Catatan</th>
                <th className="px-4 py-3 text-left text-sm">Status</th>
                <th className="px-4 py-3 text-left text-sm">Tanggal Masuk</th>
                <th className="px-4 py-3 text-left text-sm">Timestamp</th>
                <th className="px-4 py-3 text-left text-sm">Admin</th>
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
                  <td className="px-4 py-3">
                    {order.link_foto_mentah ? (
                      <a
                        href={order.link_foto_mentah}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-blue-600 hover:underline inline-flex items-center gap-1"
                      >
                        Lihat Foto
                      </a>
                    ) : (
                      '-'
                    )}
                  </td>
                  <td className="px-4 py-3">{formatPrice(order.total_bayar)}</td>
                  <td className="px-4 py-3 max-w-xs truncate" title={order.catatan}>
                    {order.catatan || '-'}
                  </td>
                  <td className="px-4 py-3">
                    <StatusBadge status={order.status_pesanan} keteranganStatus={order.keterangan_status} />
                  </td>
                  <td className="px-4 py-3">{formatDate(order.created_at)}</td>
                  <td className="px-4 py-3 text-xs text-gray-500">
                    {order.admin_updated_at ? formatDate(order.admin_updated_at) : '-'}
                  </td>
                  <td className="px-4 py-3 text-sm">
                    {order.admin?.nama_admin || '-'}
                  </td>
                  <td className="px-4 py-3">
                    <div className="flex flex-col gap-2">
                      <OrderActions
                        order={order}
                        onStatusUpdate={handleStatusUpdate}
                        isLoading={statusUpdateMutation.isPending}
                      />
                      <button
                        onClick={() => {
                          if (window.confirm('Yakin ingin menghapus pesanan ini?')) {
                            deleteMutation.mutate(order.kode_tiket);
                          }
                        }}
                        disabled={deleteMutation.isPending}
                        className="text-xs text-red-600 hover:text-red-800 text-left mt-2 flex items-center gap-1"
                      >
                        <Trash2 className="w-3 h-3" />
                        Hapus Pesanan
                      </button>
                    </div>
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

function OrderActions({ order, onStatusUpdate, isLoading }) {
  // Cek apakah status adalah revisi berdasarkan keterangan_status
  const isRevisi = order.keterangan_status === 'Revisi';
  const initialStatus = isRevisi ? 'revisi' : order.status_pesanan;

  const [selectedStatus, setSelectedStatus] = useState(initialStatus); // Default mengikuti status pesanan saat ini
  const [linkHasil, setLinkHasil] = useState(order.link_foto_hasil || ''); // Nilai awal dari database
  const [error, setError] = useState('');

  // Update selectedStatus dan linkHasil ketika order data berubah (setelah refetch)
  useEffect(() => {
    const newIsRevisi = order.keterangan_status === 'Revisi';
    const newStatus = newIsRevisi ? 'revisi' : order.status_pesanan;
    setSelectedStatus(newStatus);
    setLinkHasil(order.link_foto_hasil || '');
  }, [order.status_pesanan, order.keterangan_status, order.link_foto_hasil]);

  // Menentukan pilihan dropdown berdasarkan status saat ini
  const getStatusOptions = () => {
    const isRevisi = order.keterangan_status === 'Revisi';
    const currentStatus = isRevisi ? 'revisi' : order.status_pesanan;

    switch (currentStatus) {
      case 'terkirim':
        return ['terkirim', 'diproses'];
      case 'diproses':
        return ['diproses', 'selesai', 'revisi'];
      case 'selesai':
        return ['selesai', 'revisi'];
      case 'revisi':
        return ['revisi', 'selesai'];
      case 'dibatalkan':
        return ['dibatalkan'];
      default:
        return ['terkirim', 'diproses', 'selesai', 'revisi', 'dibatalkan'];
    }
  };

  const statusOptions = getStatusOptions();

  const handleUpdate = () => {
    setError('');

    const requestBody = {
      status: selectedStatus
    };

    // Jika pilihan dropdown adalah selesai atau revisi, sertakan link_hasil
    if (selectedStatus === 'selesai' || selectedStatus === 'revisi') {
      requestBody.link_hasil = linkHasil;
    }

    onStatusUpdate(order.kode_tiket, requestBody);
  };

  // Jika status dibatalkan, tampilkan teks saja
  if (order.status_pesanan === 'dibatalkan') {
    return <span className="text-xs text-gray-500">Ditolak</span>;
  }

  // Penentuan visibilitas input URL:
  // Muncul jika pilih 'selesai' atau 'revisi'
  const showUrlInput = (selectedStatus === 'selesai') || (selectedStatus === 'revisi');

  return (
    <div className="space-y-2">
      {/* Error Message */}
      {error && (
        <div className="text-xs text-red-600 bg-red-50 p-2 rounded">
          {error}
        </div>
      )}

      {/* Dropdown Status */}
      <div className="flex gap-2">
        <select
          value={selectedStatus}
          onChange={(e) => setSelectedStatus(e.target.value)}
          className="text-sm border border-gray-300 rounded px-2 py-1 flex-1"
          disabled={isLoading}
        >
          {statusOptions.map((s) => (
            <option key={s} value={s}>
              {s.charAt(0).toUpperCase() + s.slice(1)}
            </option>
          ))}
        </select>
        <button
          onClick={handleUpdate}
          disabled={isLoading}
          className="text-xs bg-dstudio-dark text-white px-3 py-1 rounded hover:bg-gray-800 disabled:opacity-50"
        >
          Update
        </button>
      </div>

      {/* Input URL Kondisional */}
      {showUrlInput && (
        <div className="space-y-1">
          <label className="text-xs text-gray-600 font-medium">URL Foto Hasil</label>
          <input
            type="text"
            value={linkHasil}
            onChange={(e) => setLinkHasil(e.target.value)}
            placeholder="https://drive.google.com/...."
            className="text-sm border border-gray-300 rounded px-2 py-1 w-full"
          />
        </div>
      )}
    </div>
  );
}
