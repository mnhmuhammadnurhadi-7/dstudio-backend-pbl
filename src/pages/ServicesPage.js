import { useQuery } from '@tanstack/react-query';
import { PublicLayout } from '../components/layout/PublicLayout';
import { ServiceCard } from '../components/services/ServiceCard';
import { AlertBanner } from '../components/common/Alert';
import { servicesApi } from '../services/servicesApi';

export function ServicesPage() {
  const { data, isLoading, error } = useQuery({ queryKey: ['services'], queryFn: servicesApi.getServices });

  const services = data?.services || [];

  return (
    <PublicLayout>
      {/* Hero */}
      <section className="bg-dstudio-dark py-16 px-6 text-center">
        <h1 className="text-4xl font-bold text-white">Layanan Kami</h1>
        <p className="text-gray-300 mt-4">Pilih layanan edit foto sesuai kebutuhan Anda</p>
      </section>

      {/* Services Grid */}
      <section className="py-16 px-6 bg-gray-50">
        <div className="max-w-6xl mx-auto">
          {error && <AlertBanner type="error" message="Gagal memuat layanan" />}
          
          {isLoading ? (
            <div className="text-center py-12">Memuat layanan...</div>
          ) : services.length === 0 ? (
            <p className="text-gray-500 text-center py-12">
              Tidak ada layanan tersedia saat ini.
            </p>
          ) : (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {services.map((service) => (
                <ServiceCard key={service.id_layanan} service={service} />
              ))}
            </div>
          )}
        </div>
      </section>
    </PublicLayout>
  );
}
