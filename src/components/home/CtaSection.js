import { Link } from 'react-router-dom';

export function CtaSection() {
  return (
    <section className="bg-dstudio-gold py-16 text-center">
      <div className="max-w-4xl mx-auto px-6">
        <h2 className="text-3xl font-bold text-dstudio-dark mb-6">
          Siap Edit Foto Anda?
        </h2>
        <p className="text-dstudio-dark mb-8 text-lg">
          Pesan sekarang dan dapatkan hasil edit foto berkualitas
        </p>
        <Link
          to="/pesan/step-1"
          className="inline-block bg-dstudio-dark text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition"
        >
          Mulai Pemesanan
        </Link>
      </div>
    </section>
  );
}
