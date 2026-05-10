import { Link } from 'react-router-dom';

export function HeroSection({ title, subtitle }) {
  return (
    <section className="bg-dstudio-dark py-20 px-6 text-center">
      <div className="max-w-4xl mx-auto">
        <h1 className="text-4xl md:text-6xl font-bold text-white mb-6">
          {title}
        </h1>
        <p className="text-xl md:text-2xl text-gray-300 mb-8">
          {subtitle}
        </p>
        <div className="flex flex-col sm:flex-row gap-4 justify-center">
          <Link
            to="/pesan/step-1"
            className="bg-dstudio-gold text-dstudio-dark px-8 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition"
          >
            Pesan Sekarang
          </Link>
          <Link
            to="/layanan"
            className="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-dstudio-dark transition"
          >
            Lihat Layanan Kami
          </Link>
        </div>
      </div>
    </section>
  );
}
