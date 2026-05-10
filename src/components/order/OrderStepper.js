export function OrderStepper({ current }) {
  const steps = ['Data Diri', 'Upload Foto', 'Bayar'];

  return (
    <div className="flex items-center justify-center gap-2 mb-8">
      {steps.map((step, index) => {
        const stepNum = index + 1;
        const isActive = stepNum === current;
        const isCompleted = stepNum < current;

        return (
          <div key={step} className="flex items-center">
            <div
              className={`w-8 h-8 rounded-full font-bold text-center flex items-center justify-center text-sm ${
                isActive
                  ? 'bg-dstudio-gold text-dstudio-dark'
                  : isCompleted
                  ? 'bg-green-500 text-white'
                  : 'bg-gray-600 text-gray-400'
              }`}
            >
              {stepNum}
            </div>
            <span
              className={`ml-2 text-sm font-medium ${
                isActive
                  ? 'text-dstudio-gold'
                  : isCompleted
                  ? 'text-green-500'
                  : 'text-gray-400'
              }`}
            >
              {step}
            </span>
            {index < steps.length - 1 && (
              <div
                className={`w-12 h-0.5 mx-3 ${
                  isCompleted ? 'bg-dstudio-gold' : 'bg-gray-600'
                }`}
              />
            )}
          </div>
        );
      })}
    </div>
  );
}
