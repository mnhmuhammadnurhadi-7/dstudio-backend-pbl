import { Camera, Image, Clock } from 'lucide-react';

const features = [
  {
    icon: Camera,
    title: 'Edit Profesional',
    description: 'Tim editor berpengalaman dengan kualitas standar industri',
  },
  {
    icon: Image,
    title: 'Hasil Berkualitas',
    description: 'Output high-resolution dengan koreksi warna yang sempurna',
  },
  {
    icon: Clock,
    title: 'Tepat Waktu',
    description: 'Pengerjaan sesuai estimasi dengan notifikasi status real-time',
  },
];

export function FeaturesSection() {
  return (
    <section className="py-16 px-6 bg-white">
      <div className="max-w-6xl mx-auto">
        <h2 className="text-3xl font-bold text-center text-dstudio-dark mb-12">
          Mengapa Memilih Kami?
        </h2>
        <div className="grid md:grid-cols-3 gap-8">
          {features.map((feature) => {
            const Icon = feature.icon;
            return (
              <div
                key={feature.title}
                className="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition"
              >
                <div className="w-12 h-12 bg-dstudio-gold rounded-lg flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-dstudio-dark" />
                </div>
                <h3 className="text-xl font-bold text-dstudio-dark mb-2">{feature.title}</h3>
                <p className="text-gray-600 text-sm">{feature.description}</p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
