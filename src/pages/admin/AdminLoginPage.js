import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { AlertBanner } from '../../components/common/Alert';
import { ButtonPrimary } from '../../components/common/Button';
import { useAuth } from '../../context/AuthContext';
import { Camera } from 'lucide-react';

export function AdminLoginPage() {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [formData, setFormData] = useState({ username: '', password: '' });
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setLoading(true);

    try {
      const response = await login(formData);
      if (response.success) {
        navigate('/admin/dashboard');
      } else {
        setError(response.error || 'Login gagal');
      }
    } catch (err) {
      setError('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-dstudio-dark flex items-center justify-center px-4">
      <div className="bg-dstudio-cream rounded-lg shadow-xl p-8 max-w-md w-full">
        <div className="text-center mb-8">
          <div className="flex items-center justify-center gap-2 mb-4">
            <Camera className="w-8 h-8 text-dstudio-gold" />
            <span className="font-bold text-2xl text-dstudio-dark">DStudio Admin</span>
          </div>
        </div>

        {error && <AlertBanner type="error" message={error} />}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input
              type="text"
              value={formData.username}
              onChange={(e) => setFormData({ ...formData, username: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
              required
            />
          </div>

          <ButtonPrimary type="submit" fullWidth disabled={loading}>
            {loading ? 'Memproses...' : 'Login'}
          </ButtonPrimary>
        </form>
      </div>
    </div>
  );
}
