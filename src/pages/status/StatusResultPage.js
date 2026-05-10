import { useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { useQuery, useMutation } from '@tanstack/react-query';
import { PublicLayout } from '../../components/layout/PublicLayout';
import { StatusBadge } from '../../components/common/Badge';
import { StaticRatingStars, RatingStars } from '../../components/common/RatingStars';
import { AlertBanner } from '../../components/common/Alert';
import { orderApi } from '../../services/orderApi';
import { Loader2, ExternalLink, AlertCircle } from 'lucide-react';

export function StatusResultPage() {
  const { id } = useParams();
  const [ratingError, setRatingError] = useState(null);

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['order', id],
    queryFn: () => orderApi.getOrder(id),
    enabled: !!id,
  });

  const rateMutation = useMutation({
    mutationFn: orderApi.rateOrder,
    onSuccess: () => {
      refetch();
    },
    onError: () => {
      setRatingError('Gagal memberikan rating');
    },
  });

  const handleRate = (value) => {
    setRatingError(null);
    rateMutation.mutate({ kode_tiket: id, nilai_rating: value });
  };

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

  // Map status to step
  const getStepFromStatus = (status) => {
    switch (status) {
      case 'terkirim':
      case 'dibatalkan':
        return 1;
      case 'diproses':
      case 'revisi':
        return 2;
      case 'selesai':
        return 3;
      default:
        return 1;
    }
  };

  const step = getStepFromStatus(order?.status_pesanan);

  const waMessage = order
    ? encodeURIComponent(
        `Halo DStudio! Saya ingin menanyakan pesanan saya.\n` +
          `Kode Tiket: #${order.kode_tiket}\n` +
          `Nama: ${order.nama_pelanggan}`
      )
    : '';

  return (
    <PublicLayout>
      {/* Hero */}
      <section className="bg-dstudio-dark py-12 px-6 text-center">
        <h1 className="text-4xl font-bold text-dstudio-gold font-mono mb-2">
          #{order?.kode_tiket || id}
        </h1>
        {order && <StatusBadge status={order.status_pesanan} />}
      </section>

      <section className="py-8 px-6 bg-gray-50">
        <div className="max-w-3xl mx-auto space-y-6">
          {isLoading ? (
            <div className="text-center py-12">Memuat...</div>
          ) : !order ? (
            <AlertBanner type="error" message="Pesanan tidak ditemukan" />
          ) : (
            <>
              {/* Status Stepper */}
              <div className="bg-white rounded-lg shadow-md p-6">
                <div className="flex items-center justify-center gap-4">
                  {[1, 2, 3].map((s) => {
                    const isActive = s === step;
                    const isCompleted = s < step || order.status_pesanan === 'selesai';
                    const labels = ['Terkirim', 'Diproses', 'Selesai'];
                    return (
                      <div key={s} className="flex items-center">
                        <div
                          className={`w-10 h-10 rounded-full flex items-center justify-center font-bold ${
                            isCompleted
                              ? 'bg-green-500 text-white'
                              : isActive
                              ? 'bg-dstudio-gold text-dstudio-dark'
                              : 'bg-gray-200 text-gray-400'
                          }`}
                        >
                          {isCompleted ? (
                            '✓'
                          ) : s === 2 && order.status_pesanan === 'diproses' ? (
                            <Loader2 className="w-5 h-5 animate-spin" />
                          ) : (
                            s
                          )}
                        </div>
                        <span className="ml-2 text-sm font-medium">{labels[s - 1]}</span>
                        {s < 3 && <div className={`w-8 h-0.5 mx-2 ${isCompleted ? 'bg-dstudio-gold' : 'bg-gray-300'}`} />}
                      </div>
                    );
                  })}
                </div>
              </div>

              {/* Revision Alert */}
              {order.status_pesanan === 'revisi' && (
                <div className="bg-red-100 border-2 border-red-500 rounded-lg p-4">
                  <div className="flex gap-3">
                    <AlertCircle className="w-5 h-5 text-red-500 flex-shrink-0" />
                    <div>
                      <p className="font-semibold text-red-800">Perlu Revisi</p>
                      <p className="text-red-700 text-sm">{order.catatan_revisi}</p>
                    </div>
                  </div>
                </div>
              )}

              {/* Order Details */}
              <div className="bg-white rounded-lg shadow-md p-6">
                <h2 className="text-lg font-bold text-dstudio-dark mb-4">Detail Pesanan</h2>
                <div className="grid md:grid-cols-2 gap-4">
                  <div>
                    <p className="text-gray-500 text-sm">Nama</p>
                    <p className="font-semibold">{order.nama_pelanggan}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">No WhatsApp</p>
                    <p className="font-semibold">{order.no_wa}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">Layanan</p>
                    <p className="font-semibold">{order.layanan?.nama_layanan}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">Total Bayar</p>
                    <p className="font-semibold">{formatPrice(order.total_bayar)}</p>
                  </div>
                  <div>
                    <p className="text-gray-500 text-sm">Tanggal Pesan</p>
                    <p className="font-semibold">{formatDate(order.created_at)}</p>
                  </div>
                  {order.selesai_at && (
                    <div>
                      <p className="text-gray-500 text-sm">Tanggal Selesai</p>
                      <p className="font-semibold">{formatDate(order.selesai_at)}</p>
                    </div>
                  )}
                  <div className="md:col-span-2">
                    <p className="text-gray-500 text-sm">Catatan</p>
                    <p className="font-semibold">{order.catatan || '-'}</p>
                  </div>
                </div>
              </div>

              {/* Status Message */}
              <div className="bg-white rounded-lg shadow-md p-6">
                <p className="text-gray-700 mb-4">{order.keterangan_status}</p>
                {order.whatsappNumber && (
                  <a
                    href={`https://wa.me/${order.whatsappNumber}?text=${waMessage}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-block bg-green-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition"
                  >
                    Hubungi via WhatsApp
                  </a>
                )}
                {order.status_pesanan === 'selesai' && order.link_foto_hasil && (
                  <a
                    href={order.link_foto_hasil}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 bg-dstudio-gold text-dstudio-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition ml-2"
                  >
                    <ExternalLink className="w-4 h-4" />
                    Lihat Hasil Foto
                  </a>
                )}
              </div>

              {/* Rating */}
              {order.status_pesanan === 'selesai' && !order.rating && (
                <div className="bg-white rounded-lg shadow-md p-6">
                  <h2 className="text-lg font-bold text-dstudio-dark mb-4">Berikan Rating</h2>
                  {ratingError && <AlertBanner type="error" message={ratingError} />}
                  <RatingStars onSubmit={handleRate} disabled={rateMutation.isLoading} />
                </div>
              )}

              {order.rating && (
                <div className="bg-white rounded-lg shadow-md p-6">
                  <h2 className="text-lg font-bold text-dstudio-dark mb-2">Rating Anda</h2>
                  <StaticRatingStars value={order.rating.nilai_rating} />
                </div>
              )}

              {/* Back Link */}
              <div className="text-center">
                <Link to="/cek-status" className="text-dstudio-gold font-semibold hover:underline">
                  ← Cek Status Lainnya
                </Link>
              </div>
            </>
          )}
        </div>
      </section>
    </PublicLayout>
  );
}
