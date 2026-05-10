import { useQuery } from '@tanstack/react-query';
import { PublicLayout } from '../components/layout/PublicLayout';
import { HeroSection } from '../components/home/HeroSection';
import { AboutSection } from '../components/home/AboutSection';
import { FeaturesSection } from '../components/home/FeaturesSection';
import { CtaSection } from '../components/home/CtaSection';
import { AlertBanner } from '../components/common/Alert';
import { homeApi } from '../services/homeApi';

export function HomePage() {
  const { data, isLoading, error } = useQuery({ queryKey: ['home'], queryFn: homeApi.getHome });

  return (
    <PublicLayout>
      {error && (
        <div className="max-w-4xl mx-auto mt-4 px-4">
          <AlertBanner type="error" message="Gagal memuat data halaman" />
        </div>
      )}
      
      <HeroSection
        title={isLoading ? 'DStudio Photography' : data?.heroTitle}
        subtitle={isLoading ? 'Jasa Edit Foto Profesional' : data?.heroSubtitle}
      />
      
      <AboutSection text={isLoading ? 'Memuat...' : data?.aboutText} />
      
      <FeaturesSection />
      
      <CtaSection />
    </PublicLayout>
  );
}
