export function ButtonPrimary({ children, onClick, type = 'button', fullWidth = false, disabled = false }) {
  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={`bg-dstudio-gold text-dstudio-dark font-semibold rounded-lg transition hover:bg-yellow-500 ${
        fullWidth ? 'w-full block py-3' : 'px-6 py-2'
      } ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`}
    >
      {children}
    </button>
  );
}

export function ButtonSecondary({ children, onClick, type = 'button', fullWidth = false, disabled = false }) {
  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled}
      className={`bg-gray-200 text-gray-800 font-semibold rounded-lg transition hover:bg-gray-300 ${
        fullWidth ? 'w-full block py-3' : 'px-6 py-2'
      } ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`}
    >
      {children}
    </button>
  );
}
