import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { OrderStepper } from '../../components/order/OrderStepper';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary, ButtonSecondary } from '../../components/common/Button';
import { useOrder } from '../../context/OrderContext';
import { orderApi } from '../../services/orderApi';
import { Info } from 'lucide-react';

export function Step2Page() {
  const navigate = useNavigate();
  const { orderState, setStep2 } = useOrder();
  const [photoLink, setPhotoLink] = useState(orderState.photo_link || '');
  const [errors, setErrors] = useState({});

  // Guard: redirect if step 1 not completed
  useEffect(() => {
    if (!orderState.service_id) {
      navigate('/pesan/step-1');
    }
  }, [orderState.service_id, navigate]);

  const mutation = useMutation({
    mutationFn: orderApi.postStep2,
    onSuccess: (response) => {
      if (response.success) {
        setStep2({ photo_link: photoLink });
        navigate('/pesan/step-3');
      }
    },
    onError: (err) => {
      setErrors(err.response?.data?.errors || {});
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    setErrors({});
    mutation.mutate({ photo_link: photoLink });
  };

  const handleBack = () => {
    navigate('/pesan/step-1');
  };

  if (!orderState.service_id) return null;

  return (
    <PublicLayout>
      <div className="bg-dstudio-dark min-h-screen py-12 px-6">
        <div className="max-w-2xl mx-auto">
          <OrderStepper current={2} />

          <div className="bg-white rounded-lg shadow-md p-8">
            <h1 className="text-2xl font-bold text-dstudio-dark mb-6">Link Foto</h1>

            {mutation.error && (
              <AlertBanner type="error" message="Terjadi kesalahan. Silakan coba lagi." />
            )}

            {/* Info Box */}
            <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
              <div className="flex gap-3">
                <Info className="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" />
                <div className="text-sm text-blue-800">
                  <p className="font-medium mb-1">Panduan Upload Foto ke Google Drive:</p>
                  <ol className="list-decimal list-inside space-y-1 text-blue-700">
                    <li>Upload foto Anda ke Google Drive</li>
                    <li>Pilih folder/foto yang ingin dibagikan</li>
                    <li>Klik kanan &gt; Share/Bagikan</li>
                    <li>Ubah akses ke "Anyone with the link"</li>
                    <li>Copy link dan paste di form di bawah</li>
                  </ol>
                </div>
              </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Link Google Drive Foto Anda *
                </label>
                <input
                  type="url"
                  value={photoLink}
                  onChange={(e) => setPhotoLink(e.target.value)}
                  placeholder="https://drive.google.com/..."
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold ${
                    errors.photo_link ? 'border-red-500' : 'border-gray-300'
                  }`}
                  required
                />
                {errors.photo_link && <p className="text-red-500 text-sm mt-1">{errors.photo_link}</p>}
              </div>

              <div className="flex gap-4 pt-4">
                <ButtonSecondary onClick={handleBack} fullWidth>
                  Kembali
                </ButtonSecondary>
                <ButtonPrimary type="submit" fullWidth disabled={mutation.isLoading}>
                  {mutation.isLoading ? 'Memproses...' : 'Lanjutkan'}
                </ButtonPrimary>
              </div>
            </form>
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}
