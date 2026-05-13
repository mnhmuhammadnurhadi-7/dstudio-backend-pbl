import { useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary } from '../../components/common/Button';
import { adminApi } from '../../services/adminApi';

export function AdminEditUserPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [formData, setFormData] = useState({
    nama_admin: '',
    username: '',
    password: '',
    password_confirmation: '',
    role: 'admin',
  });
  const [errors, setErrors] = useState({});

  const { data, isLoading } = useQuery({
    queryKey: ['admin', id],
    queryFn: () => adminApi.getAdmin(id),
    onSuccess: (adminData) => {
      setFormData({
        nama_admin: adminData.nama_admin,
        username: adminData.username,
        password: '',
        password_confirmation: '',
        role: adminData.role,
      });
    },
  });

  const mutation = useMutation({
    mutationFn: (data) => adminApi.updateAdmin(id, data),
    onSuccess: () => {
      navigate('/admin/admins');
    },
    onError: (err) => {
      setErrors(err.response?.data?.errors || {});
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    setErrors({});
    
    const submitData = { ...formData };
    if (!submitData.password) {
      delete submitData.password;
      delete submitData.password_confirmation;
    }
    
    mutation.mutate(submitData);
  };

  return (
    <AdminLayout>
      <div className="bg-dstudio-dark min-h-screen py-12 px-6">
        <div className="max-w-2xl mx-auto">
          <div className="bg-white rounded-lg shadow-md p-8">
            <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Edit Admin</h1>

            {mutation.error && !errors && (
              <AlertBanner type="error" message="Terjadi kesalahan. Silakan coba lagi." />
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Nama Admin *
                </label>
                <input
                  type="text"
                  value={formData.nama_admin}
                  onChange={(e) => setFormData({ ...formData, nama_admin: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.nama_admin ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                />
                {errors.nama_admin && <p className="text-red-500 text-sm mt-1">{errors.nama_admin}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Username *
                </label>
                <input
                  type="text"
                  value={formData.username}
                  onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.username ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                />
                {errors.username && <p className="text-red-500 text-sm mt-1">{errors.username}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Password Baru
                </label>
                <input
                  type="password"
                  value={formData.password}
                  onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                  placeholder="Kosongkan jika tidak ingin mengubah password"
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.password ? 'border-red-500' : 'border-gray-300'
                  }`}
                />
                {errors.password && <p className="text-red-500 text-sm mt-1">{errors.password}</p>}
              </div>

              {formData.password && (
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Konfirmasi Password *
                  </label>
                  <input
                    type="password"
                    value={formData.password_confirmation}
                    onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                    className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                      errors.password_confirmation ? 'border-red-500' : 'border-gray-300'
                    }`}
                    required
                  />
                  {errors.password_confirmation && <p className="text-red-500 text-sm mt-1">{errors.password_confirmation}</p>}
                </div>
              )}

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Role *
                </label>
                <select
                  value={formData.role}
                  onChange={(e) => setFormData({ ...formData, role: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.role ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                >
                  <option value="admin">Admin</option>
                  <option value="superadmin">Super Admin</option>
                </select>
                {errors.role && <p className="text-red-500 text-sm mt-1">{errors.role}</p>}
              </div>

              <div className="pt-4 flex gap-4">
                <ButtonPrimary type="submit" disabled={mutation.isPending}>
                  {mutation.isPending ? 'Menyimpan...' : 'Simpan Perubahan'}
                </ButtonPrimary>
                <button
                  type="button"
                  onClick={() => navigate('/admin/admins')}
                  className="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                >
                  Batal
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
