import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { OrderStepper } from '../../components/order/OrderStepper';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary } from '../../components/common/Button';
import { useOrder } from '../../context/OrderContext';
import { orderApi } from '../../services/orderApi';

export function Step1Page() {
  const navigate = useNavigate();
  const { setStep1 } = useOrder();
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    service_id: '',
    notes: '',
  });
  const [errors, setErrors] = useState({});

  const { data, isLoading } = useQuery({ queryKey: ['step1'], queryFn: orderApi.getStep1 });

  const mutation = useMutation({
    mutationFn: orderApi.postStep1,
    onSuccess: (response) => {
      if (response.success) {
        setStep1(formData);
        navigate('/pesan/step-2');
      }
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

  const services = data?.services || [];

  return (
    <PublicLayout>
      <div className="bg-dstudio-dark min-h-screen py-12 px-6">
        <div className="max-w-2xl mx-auto">
          <OrderStepper current={1} />

          <div className="bg-white rounded-lg shadow-md p-8">
            <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Data Diri</h1>

            {mutation.error && !errors && (
              <AlertBanner type="error" message="Terjadi kesalahan. Silakan coba lagi." />
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Nama Lengkap *
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
                  No WhatsApp *
                </label>
                <input
                  type="text"
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.phone ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                />
                {errors.phone && <p className="text-red-500 text-sm mt-1">{errors.phone}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Pilih Layanan *
                </label>
                <select
                  value={formData.service_id}
                  onChange={(e) => setFormData({ ...formData, service_id: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.service_id ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                  disabled={isLoading}
                >
                  <option value="">Pilih layanan...</option>
                  {services.map((service) => (
                    <option key={service.id_layanan} value={service.id_layanan}>
                      {service.nama_layanan} - Rp {service.harga.toLocaleString('id-ID')}
                    </option>
                  ))}
                </select>
                {errors.service_id && <p className="text-red-500 text-sm mt-1">{errors.service_id}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Catatan
                </label>
                <textarea
                  value={formData.notes}
                  onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                  placeholder="Catatan tambahan..."
                  rows="3"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                />
              </div>

              <div className="pt-4">
                <ButtonPrimary type="submit" fullWidth disabled={mutation.isPending}>
                  {mutation.isPending ? 'Memproses...' : 'Lanjutkan'}
                </ButtonPrimary>
              </div>
            </form>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}
