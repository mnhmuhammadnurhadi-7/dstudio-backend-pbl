import { useState, useEffect } from 'react';
import { useQuery, useMutation } from '@tanstack/react-query';
import { AdminLayout } from '../../components/layout/AdminLayout';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary } from '../../components/common/Button';
import { adminApi } from '../../services/adminApi';

export function AdminCmsPage() {
  const [formData, setFormData] = useState({
    hero_title: '',
    hero_subtitle: '',
    about_text: '',
    nomor_wa_bisnis: '',
    qris_image_path: '',
    instagram_url: '',
  });
  const [success, setSuccess] = useState(false);

  const { data, isLoading } = useQuery({
    queryKey: ['cms'],
    queryFn: adminApi.getCms,
  });

  useEffect(() => {
    if (data?.contents) {
      const contents = data.contents;
      setFormData({
        hero_title: contents.hero_title?.setting_value || '',
        hero_subtitle: contents.hero_subtitle?.setting_value || '',
        about_text: contents.about_text?.setting_value || '',
        nomor_wa_bisnis: contents.nomor_wa_bisnis?.setting_value || '',
        qris_image_path: contents.qris_image_path?.setting_value || '',
        instagram_url: contents.instagram_url?.setting_value || '',
      });
    }
  }, [data]);

  const mutation = useMutation({
    mutationFn: adminApi.updateCms,
    onSuccess: () => {
      setSuccess(true);
      setTimeout(() => setSuccess(false), 3000);
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate(formData);
  };

  const isValidImageUrl = (url) => {
    return url && (url.startsWith('http://') || url.startsWith('https://'));
  };

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold text-dstudio-dark mb-6">CMS - Content Management</h1>

      <div className="bg-white rounded-lg shadow p-8 max-w-3xl">
        {success && <AlertBanner type="success" message="Perubahan berhasil disimpan!" />}
        {mutation.error && (
          <AlertBanner type="error" message="Gagal menyimpan perubahan" />
        )}

        {isLoading ? (
          <div className="text-center py-8">Memuat...</div>
        ) : (
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Judul Hero
              </label>
              <input
                type="text"
                value={formData.hero_title}
                onChange={(e) => setFormData({ ...formData, hero_title: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Subjudul Hero
              </label>
              <input
                type="text"
                value={formData.hero_subtitle}
                onChange={(e) => setFormData({ ...formData, hero_subtitle: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Teks Tentang Kami
              </label>
              <textarea
                value={formData.about_text}
                onChange={(e) => setFormData({ ...formData, about_text: e.target.value })}
                rows="4"
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Nomor WhatsApp Bisnis
              </label>
              <input
                type="text"
                value={formData.nomor_wa_bisnis}
                onChange={(e) => setFormData({ ...formData, nomor_wa_bisnis: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                URL Gambar QRIS
              </label>
              <input
                type="text"
                value={formData.qris_image_path}
                onChange={(e) => setFormData({ ...formData, qris_image_path: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
              {isValidImageUrl(formData.qris_image_path) && (
                <div className="mt-2">
                  <img
                    src={formData.qris_image_path}
                    alt="QRIS Preview"
                    className="w-48 h-48 object-contain border rounded"
                    onError={(e) => {
                      e.target.style.display = 'none';
                    }}
                  />
                </div>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                URL Instagram
              </label>
              <input
                type="text"
                value={formData.instagram_url}
                onChange={(e) => setFormData({ ...formData, instagram_url: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              />
            </div>

            <div className="pt-4">
              <ButtonPrimary type="submit" fullWidth disabled={mutation.isLoading}>
                {mutation.isLoading ? 'Menyimpan...' : 'Simpan Perubahan'}
              </ButtonPrimary>
            </div>
          </form>
        )}
      </div>
    </AdminLayout>
  );
}
