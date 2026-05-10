import { Link } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { RoleBadge } from '../../components/common/Badge';
import { AlertBanner } from '../../components/common/Alert';
import { useAuth } from '../../context/AuthContext';
import { adminApi } from '../../services/adminApi';
import { Plus, Trash2 } from 'lucide-react';

export function AdminUsersPage() {
  const { authState } = useAuth();
  const { data, isLoading, refetch } = useQuery({
    queryKey: ['admins'],
    queryFn: adminApi.getAdmins,
  });

  const deleteMutation = useMutation({
    mutationFn: adminApi.deleteAdmin,
    onSuccess: () => refetch(),
  });

  const admins = data?.admins || [];

  const handleDelete = (id) => {
    if (window.confirm('Yakin ingin menghapus admin ini?')) {
      deleteMutation.mutate(id);
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    });
  };

  return (
    <AdminLayout>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold text-dstudio-dark">Kelola Admin</h1>
        <Link
          to="/admin/admins/create"
          className="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition inline-flex items-center gap-2"
        >
          <Plus className="w-4 h-4" />
          Tambah Admin
        </Link>
      </div>

      {deleteMutation.error && (
        <AlertBanner type="error" message="Gagal menghapus admin" />
      )}

      <div className="bg-white rounded-lg shadow overflow-x-auto">
        {isLoading ? (
          <div className="p-8 text-center">Memuat...</div>
        ) : (
          <table className="w-full">
            <thead className="bg-dstudio-dark text-white">
              <tr>
                <th className="px-4 py-3 text-left text-sm">Nama</th>
                <th className="px-4 py-3 text-left text-sm">Username</th>
                <th className="px-4 py-3 text-left text-sm">Role</th>
                <th className="px-4 py-3 text-left text-sm">Dibuat</th>
                <th className="px-4 py-3 text-left text-sm">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {admins.map((admin) => {
                const isCurrentUser = admin.id === authState.adminId;
                return (
                  <tr
                    key={admin.id}
                    className={`border-b hover:bg-gray-50 ${isCurrentUser ? 'bg-yellow-50' : ''}`}
                  >
                    <td className="px-4 py-3 font-medium">
                      {admin.nama_admin}
                      {isCurrentUser && (
                        <span className="ml-2 inline-block px-2 py-0.5 rounded text-xs font-semibold bg-dstudio-gold text-dstudio-dark">
                          Anda
                        </span>
                      )}
                    </td>
                    <td className="px-4 py-3">{admin.username}</td>
                    <td className="px-4 py-3">
                      <RoleBadge role={admin.role} />
                    </td>
                    <td className="px-4 py-3">{formatDate(admin.created_at)}</td>
                    <td className="px-4 py-3">
                      {!isCurrentUser && (
                        <button
                          onClick={() => handleDelete(admin.id)}
                          disabled={deleteMutation.isLoading}
                          className="text-red-600 hover:text-red-800 inline-flex items-center gap-1 text-sm"
                        >
                          <Trash2 className="w-4 h-4" />
                          Hapus
                        </button>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        )}
      </div>
    </AdminLayout>
  );
}
