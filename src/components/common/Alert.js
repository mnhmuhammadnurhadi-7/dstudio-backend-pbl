export function AlertBanner({ type, message }) {
  const styles = {
    success: 'bg-green-100 border border-green-400 text-green-800',
    error: 'bg-red-100 border border-red-400 text-red-800',
  };

  if (!message) return null;

  return (
    <div className={`p-4 rounded-lg mb-4 ${styles[type] || styles.error}`}>
      {message}
    </div>
  );
}
