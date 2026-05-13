import { Link } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { AlertBanner } from '../../components/common/Alert';
import { adminApi } from '../../services/adminApi';
import { Plus, Edit, Trash2 } from 'lucide-react';

export function AdminServicesPage() {
  const { data, isLoading, refetch } = useQuery({
    queryKey: ['admin-services'],
    queryFn: adminApi.getAdminServices,
  });

  const deleteMutation = useMutation({
    mutationFn: adminApi.deleteService,
    onSuccess: () => refetch(),
  });

  const services = Array.isArray(data) ? data : data?.services || [];

  const handleDelete = (id) => {
    if (window.confirm('Yakin ingin menghapus layanan ini?')) {
      deleteMutation.mutate(id);
    }
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
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold text-dstudio-dark">Kelola Layanan</h1>
        <Link
          to="/admin/services/create"
          className="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition inline-flex items-center gap-2"
        >
          <Plus className="w-4 h-4" />
          Tambah Layanan
        </Link>
      </div>

      {deleteMutation.error && (
        <AlertBanner type="error" message="Gagal menghapus layanan" />
      )}

      <div className="bg-white rounded-lg shadow overflow-x-auto">
        {isLoading ? (
          <div className="p-8 text-center">Memuat...</div>
        ) : (
          <table className="w-full">
            <thead className="bg-dstudio-dark text-white">
              <tr>
                <th className="px-4 py-3 text-left text-sm">Nama</th>
                <th className="px-4 py-3 text-left text-sm">Harga</th>
                <th className="px-4 py-3 text-left text-sm">Deskripsi</th>
                <th className="px-4 py-3 text-left text-sm">Status</th>
                <th className="px-4 py-3 text-left text-sm">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {services.map((service) => (
                <tr key={service.id_layanan} className="border-b hover:bg-gray-50">
                  <td className="px-4 py-3 font-medium">{service.nama_layanan}</td>
                  <td className="px-4 py-3">{formatPrice(service.harga)}</td>
                  <td className="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">
                    {service.deskripsi || '-'}
                  </td>
                  <td className="px-4 py-3">
                    {service.is_active ? (
                      <span className="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        Aktif
                      </span>
                    ) : (
                      <span className="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        Nonaktif
                      </span>
                    )}
                  </td>
                  <td className="px-4 py-3">
                    <div className="flex gap-2">
                      <Link
                        to={`/admin/services/${service.id_layanan}/edit`}
                        className="text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 text-sm"
                      >
                        <Edit className="w-4 h-4" />
                        Edit
                      </Link>
                      <button
                        onClick={() => handleDelete(service.id_layanan)}
                        disabled={deleteMutation.isPending}
                        className="text-red-600 hover:text-red-800 inline-flex items-center gap-1 text-sm"
                      >
                        <Trash2 className="w-4 h-4" />
                        Hapus
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
