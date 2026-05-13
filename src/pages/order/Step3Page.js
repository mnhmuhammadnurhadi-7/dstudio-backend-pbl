import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { OrderStepper } from '../../components/order/OrderStepper';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary, ButtonSecondary } from '../../components/common/Button';
import { useOrder } from '../../context/OrderContext';
import { orderApi } from '../../services/orderApi';

export function Step3Page() {
  const navigate = useNavigate();
  const { orderState, setTicket, reset } = useOrder();
  const [error, setError] = useState(null);

  // Guard: redirect if step 2 not completed
  useEffect(() => {
    if (!orderState.photo_link) {
      navigate('/pesan/step-1');
    }
  }, [orderState.photo_link, navigate]);

  const { data, isLoading } = useQuery({
    queryKey: ['step3', orderState.service_id],
    queryFn: () => orderApi.getStep3(orderState.service_id),
    enabled: !!orderState.service_id,
  });

  const mutation = useMutation({
    mutationFn: orderApi.postStep3,
    onSuccess: (response) => {
      if (response.success && response.ticket_id) {
        setTicket(response.ticket_id);
        navigate('/pesan/selesai');
      }
    },
    onError: (err) => {
      setError('Terjadi kesalahan saat membuat pesanan');
    },
  });

  const handleSubmit = () => {
    setError(null);
    mutation.mutate({
      name: orderState.name,
      phone: orderState.phone,
      service_id: orderState.service_id,
      notes: orderState.notes,
      photo_link: orderState.photo_link,
    });
  };

  const handleBack = () => {
    navigate('/pesan/step-2');
  };

  if (!orderState.photo_link) return null;

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price || 0);
  };

  return (
    <PublicLayout>
      <div className="bg-dstudio-dark min-h-screen py-12 px-6">
        <div className="max-w-2xl mx-auto">
          <OrderStepper current={3} />

          <div className="bg-white rounded-lg shadow-md p-8 text-center">
            <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Pembayaran QRIS</h1>

            {error && <AlertBanner type="error" message={error} />}

            {/* QRIS Image */}
            <div className="bg-gray-100 rounded-lg p-4 inline-block mb-6">
              {data?.qris_image ? (
                <img
                  src={data.qris_image}
                  alt="QRIS Code"
                  className="w-64 h-64 object-contain"
                />
              ) : (
                <div className="w-64 h-64 bg-gray-200 flex items-center justify-center">
                  <span className="text-gray-400">QRIS Image</span>
                </div>
              )}
            </div>

            {/* Price */}
            <p className="text-2xl font-bold text-dstudio-gold mb-6">
              {isLoading ? 'Memuat...' : formatPrice(data?.service?.harga)}
            </p>

            {/* Instructions */}
            <div className="bg-yellow-50 rounded-lg p-4 text-sm text-left mb-8">
              <p className="font-semibold text-yellow-800 mb-2">Instruksi Pembayaran:</p>
              <ol className="list-decimal list-inside space-y-1 text-yellow-700">
                <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                <li>Pilih pembayaran QRIS/Scan QR</li>
                <li>Scan QR code di atas</li>
                <li>Konfirmasi pembayaran</li>
                <li>Screenshot bukti pembayaran</li>
              </ol>
            </div>

            {/* Buttons */}
            <div className="flex gap-4">
              <ButtonSecondary onClick={handleBack} fullWidth>
                Kembali
              </ButtonSecondary>
              <ButtonPrimary onClick={handleSubmit} fullWidth disabled={mutation.isPending}>
                {mutation.isPending ? 'Memproses...' : 'Konfirmasi Pesanan'}
              </ButtonPrimary>
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}
