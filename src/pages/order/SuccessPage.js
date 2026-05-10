import { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { ButtonPrimary, ButtonSecondary } from '../../components/common/Button';
import { useOrder } from '../../context/OrderContext';
import { orderApi } from '../../services/orderApi';
import { CheckCircle } from 'lucide-react';

export function SuccessPage() {
  const navigate = useNavigate();
  const { orderState, reset } = useOrder();

  // Guard: redirect if no ticket
  useEffect(() => {
    if (!orderState.ticket_id) {
      navigate('/');
    }
  }, [orderState.ticket_id, navigate]);

  const { data, isLoading } = useQuery({
    queryKey: ['order', orderState.ticket_id],
    queryFn: () => orderApi.getOrder(orderState.ticket_id),
    enabled: !!orderState.ticket_id,
  });

  const order = data;

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price || 0);
  };

  const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const waMessage = order
    ? encodeURIComponent(
        `Halo DStudio! Saya sudah melakukan pemesanan.\n` +
          `Kode Tiket: #${order.kode_tiket}\n` +
          `Nama: ${order.nama_pelanggan}\n` +
          `Layanan: ${order.layanan?.nama_layanan}\n` +
          `Total: ${formatPrice(order.total_bayar)}\n` +
          `Mohon dikonfirmasi. Terima kasih!`
      )
    : '';

  const handleNewOrder = () => {
    reset();
  };

  if (!orderState.ticket_id) return null;

  return (
    <PublicLayout>
      <div className="bg-dstudio-dark min-h-screen py-12 px-6">
        <div className="max-w-2xl mx-auto text-center">
          {/* Success Icon */}
          <div className="bg-green-500 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
            <CheckCircle className="w-12 h-12 text-white" />
          </div>

          <h1 className="text-3xl font-bold text-white mb-8">Pesanan Berhasil!</h1>

          <div className="bg-white rounded-lg shadow-md p-8">
            {isLoading ? (
              <div>Memuat detail pesanan...</div>
            ) : (
              <>
                {/* Ticket Code */}
                <div className="mb-8">
                  <p className="text-5xl font-bold text-dstudio-gold font-mono mb-2">
                    #{order?.kode_tiket}
                  </p>
                  <p className="text-gray-500 text-sm">Screenshot kode ini!</p>
                </div>

                {/* Details Grid */}
                <div className="grid grid-cols-2 gap-4 text-left mb-8">
                  <div>
                    <p className="text-gray-500 text-sm">Nama</p>
                    <p className="font-semibold">{order?.nama_pelanggan}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">No HP</p>
                    <p className="font-semibold">{order?.no_wa}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">Layanan</p>
                    <p className="font-semibold">{order?.layanan?.nama_layanan}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">Total Bayar</p>
                    <p className="font-semibold">{formatPrice(order?.total_bayar)}</p>
                  </div>
                  <div className="col-span-2">
                    <p className="text-gray-500 text-sm">Catatan</p>
                    <p className="font-semibold">{order?.catatan || '-'}</p>
                  </div>
                  <div className="col-span-2">
                    <p className="text-gray-500 text-sm">Waktu Pesan</p>
                    <p className="font-semibold">{formatDate(order?.created_at)}</p>
                  </div>
                </div>

                {/* Buttons */}
                <div className="space-y-3">
                  {order?.whatsappNumber && (
                    <a
                      href={`https://wa.me/${order.whatsappNumber}?text=${waMessage}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="block w-full py-3 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition"
                    >
                      Konfirmasi via WhatsApp
                    </a>
                  )}
                  <Link
                    to="/cek-status"
                    className="block w-full py-3 rounded-lg bg-dstudio-dark text-white font-semibold hover:bg-gray-800 transition"
                  >
                    Cek Status Tiket
                  </Link>
                  <Link
                    to="/pesan/step-1"
                    onClick={handleNewOrder}
                    className="block w-full py-3 rounded-lg bg-dstudio-gold text-dstudio-dark font-semibold hover:bg-yellow-500 transition"
                  >
                    Pesan Lagi
                  </Link>
                </div>
              </>
            )}
          </div>
        </div>
      </div>
    </PublicLayout>
  );
}
