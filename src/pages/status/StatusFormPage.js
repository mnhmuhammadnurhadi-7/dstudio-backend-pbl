import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useMutation } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary } from '../../components/common/Button';
import { orderApi } from '../../services/orderApi';

export function StatusFormPage() {
  const navigate = useNavigate();
  const [ticketCode, setTicketCode] = useState('');
  const [error, setError] = useState(null);

  const mutation = useMutation({
    mutationFn: orderApi.checkStatus,
    onSuccess: (order) => {
      if (order && order.kode_tiket) {
        navigate(`/cek-status/result/${order.kode_tiket}`);
      } else {
        setError('Kode tiket tidak ditemukan');
      }
    },
    onError: (err) => {
      setError(err.response?.data?.error || 'Terjadi kesalahan');
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    setError(null);
    mutation.mutate({ ticket_id: ticketCode });
  };

  return (
    <PublicLayout>
      {/* Hero */}
      <section className="bg-dstudio-dark py-16 px-6 text-center">
        <h1 className="text-4xl font-bold text-white">Cek Status Pesanan</h1>
        <p className="text-gray-300 mt-4">Masukkan kode tiket untuk melihat status pesanan</p>
      </section>

      {/* Form */}
      <section className="py-16 px-6 bg-gray-50">
        <div className="max-w-md mx-auto">
          <div className="bg-white rounded-lg shadow-md p-8">
            {error && <AlertBanner type="error" message={error} />}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="relative">
                <span className="absolute left-4 top-3 text-gray-400 font-bold">#</span>
                <input
                  type="text"
                  value={ticketCode}
                  onChange={(e) => setTicketCode(e.target.value.toUpperCase())}
                  placeholder="KODE TIKET"
                  className="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg text-center text-lg font-bold uppercase focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                  required
                />
              </div>

              <ButtonPrimary type="submit" fullWidth disabled={mutation.isPending}>
                {mutation.isPending ? 'Mencari...' : 'Cek Status'}
              </ButtonPrimary>
            </form>

            <p className="text-center mt-6 text-sm text-gray-600">
              Belum pesan?{' '}
              <Link to="/pesan/step-1" className="text-dstudio-gold font-semibold hover:underline">
                Pesan sekarang
              </Link>
            </p>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
