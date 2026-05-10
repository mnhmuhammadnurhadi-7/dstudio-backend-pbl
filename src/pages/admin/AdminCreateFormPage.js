import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary, ButtonSecondary } from '../../components/common/Button';
import { adminApi } from '../../services/adminApi';

export function AdminCreateFormPage() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    username: '',
    password: '',
    role: 'admin',
  });
  const [errors, setErrors] = useState({});

  const mutation = useMutation({
    mutationFn: adminApi.createAdmin,
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
    mutation.mutate(formData);
  };

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Tambah Admin</h1>

      <div className="bg-white rounded-lg shadow p-8 max-w-2xl">
        {mutation.error && !Object.keys(errors).length && (
          <AlertBanner type="error" message="Terjadi kesalahan. Silakan coba lagi." />
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Nama *
            </label>
            <input
              type="text"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                errors.name ? 'border-red-500' : 'border-gray-300'
              }`}
              required
            />
            {errors.name && <p className="text-red-500 text-sm mt-1">{errors.name}</p>}
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
            {errors.username && (
              <p className="text-red-500 text-sm mt-1">{errors.username}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Password *
            </label>
            <input
              type="password"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                errors.password ? 'border-red-500' : 'border-gray-300'
              }`}
              required
            />
            {errors.password && (
              <p className="text-red-500 text-sm mt-1">{errors.password}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Role *
            </label>
            <select
              value={formData.role}
              onChange={(e) => setFormData({ ...formData, role: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
            >
              <option value="admin">Admin</option>
              <option value="superadmin">Superadmin</option>
            </select>
          </div>

          <div className="flex gap-4 pt-4">
            <Link to="/admin/admins" className="flex-1">
              <ButtonSecondary fullWidth>Batal</ButtonSecondary>
            </Link>
            <div className="flex-1">
              <ButtonPrimary type="submit" fullWidth disabled={mutation.isLoading}>
                {mutation.isLoading ? 'Menyimpan...' : 'Simpan'}
              </ButtonPrimary>
            </div>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}
