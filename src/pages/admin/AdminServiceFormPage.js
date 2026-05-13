import { useState, useEffect } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary, ButtonSecondary } from '../../components/common/Button';
import { adminApi } from '../../services/adminApi';

export function AdminServiceFormPage() {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = !!id;

  const [formData, setFormData] = useState({
    nama_layanan: '',
    harga: '',
    deskripsi: '',
    is_active: true,
  });
  const [errors, setErrors] = useState({});

  const { data, isLoading: isLoadingService } = useQuery({
    queryKey: ['service', id],
    queryFn: () => adminApi.getService(id),
    enabled: isEdit,
  });

  useEffect(() => {
    if (data?.service) {
      setFormData({
        nama_layanan: data.service.nama_layanan,
        harga: data.service.harga,
        deskripsi: data.service.deskripsi || '',
        is_active: data.service.is_active,
      });
    }
  }, [data]);

  const mutation = useMutation({
    mutationFn: (data) => (isEdit ? adminApi.updateService(id, data) : adminApi.createService(data)),
    onSuccess: () => {
      navigate('/admin/services');
    },
    onError: (err) => {
      setErrors(err.response?.data?.errors || {});
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    setErrors({});
    mutation.mutate({
      ...formData,
      harga: Number(formData.harga),
    });
  };

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold text-dstudio-dark mb-6">
        {isEdit ? 'Edit Layanan' : 'Tambah Layanan'}
      </h1>

      <div className="bg-white rounded-lg shadow p-8 max-w-2xl">
        {mutation.error && !Object.keys(errors).length && (
          <AlertBanner type="error" message="Terjadi kesalahan. Silakan coba lagi." />
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Nama Layanan *
            </label>
            <input
              type="text"
              value={formData.nama_layanan}
              onChange={(e) => setFormData({ ...formData, nama_layanan: e.target.value })}
              maxLength={100}
              className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                errors.nama_layanan ? 'border-red-500' : 'border-gray-300'
              }`}
              required
            />
            {errors.nama_layanan && (
              <p className="text-red-500 text-sm mt-1">{errors.nama_layanan}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Harga *
            </label>
            <input
              type="number"
              value={formData.harga}
              onChange={(e) => setFormData({ ...formData, harga: e.target.value })}
              min={0}
              className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                errors.harga ? 'border-red-500' : 'border-gray-300'
              }`}
              required
            />
            {errors.harga && <p className="text-red-500 text-sm mt-1">{errors.harga}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Deskripsi
            </label>
            <textarea
              value={formData.deskripsi}
              onChange={(e) => setFormData({ ...formData, deskripsi: e.target.value })}
              rows="4"
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
            />
          </div>

          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              id="is_active"
              checked={formData.is_active}
              onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
              className="w-4 h-4 text-dstudio-gold rounded focus:ring-dstudio-gold"
            />
            <label htmlFor="is_active" className="text-sm font-medium text-gray-700">
              Aktif
            </label>
          </div>

          <div className="flex gap-4 pt-4">
            <Link to="/admin/services" className="flex-1">
              <ButtonSecondary fullWidth>Batal</ButtonSecondary>
            </Link>
            <div className="flex-1">
              <ButtonPrimary type="submit" fullWidth disabled={mutation.isPending}>
                {mutation.isPending ? 'Menyimpan...' : 'Simpan'}
              </ButtonPrimary>
            </div>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}
