export function AboutSection({ text }) {
  return (
    <section className="py-16 px-6 bg-white">
      <div className="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 className="text-3xl font-bold text-dstudio-dark mb-6">Tentang Kami</h2>
          <p className="text-gray-600 leading-relaxed">{text}</p>
        </div>
        <div className="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
          <span className="text-gray-400">Gambar Studio</span>
        </div>
      </div>
    </section>
  );
}
