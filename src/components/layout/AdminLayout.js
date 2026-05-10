import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import {
  LayoutDashboard,
  CheckCircle,
  Briefcase,
  Users,
  Settings,
  LogOut,
  Camera,
} from 'lucide-react';

export function AdminLayout({ children }) {
  const { authState, logout } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();

  const isSuperadmin = authState.adminRole === 'superadmin';

  const handleLogout = async () => {
    await logout();
    navigate('/admin/login');
  };

  const navItems = [
    { path: '/admin/dashboard', label: 'Tabel Antrean', icon: LayoutDashboard },
    { path: '/admin/completed', label: 'Pesanan Selesai', icon: CheckCircle },
    ...(isSuperadmin ? [
      { path: '/admin/services', label: 'Kelola Layanan', icon: Briefcase },
      { path: '/admin/admins', label: 'Kelola Admin', icon: Users },
      { path: '/admin/cms', label: 'CMS', icon: Settings },
    ] : []),
  ];

  return (
    <div className="flex min-h-screen">
      {/* Sidebar */}
      <aside className="w-64 bg-dstudio-dark text-white flex flex-col">
        <div className="p-6 border-b border-gray-700">
          <Link to="/admin/dashboard" className="flex items-center gap-2">
            <Camera className="w-6 h-6 text-dstudio-gold" />
            <span className="font-bold text-xl">DStudio Admin</span>
          </Link>
        </div>

        <nav className="flex-1 py-4">
          {navItems.map((item) => {
            const isActive = location.pathname === item.path;
            const Icon = item.icon;
            return (
              <Link
                key={item.path}
                to={item.path}
                className={`flex items-center gap-3 px-6 py-3 transition ${
                  isActive
                    ? 'bg-gray-800 border-l-4 border-dstudio-gold text-white'
                    : 'text-gray-400 hover:text-white hover:bg-gray-800'
                }`}
              >
                <Icon className="w-5 h-5" />
                {item.label}
              </Link>
            );
          })}
        </nav>

        <div className="p-4 border-t border-gray-700">
          <button
            onClick={handleLogout}
            className="flex items-center gap-3 px-6 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg w-full transition"
          >
            <LogOut className="w-5 h-5" />
            Logout
          </button>
        </div>
      </aside>

      {/* Main Area */}
      <div className="flex-1 flex flex-col">
        {/* Header */}
        <header className="bg-white border-b px-8 py-4">
          <h2 className="text-gray-700">
            Selamat datang, <span className="font-semibold text-dstudio-dark">{authState.adminName}</span>
          </h2>
        </header>

        {/* Content */}
        <main className="flex-1 p-8 bg-gray-50">{children}</main>
      </div>
    </div>
  );
}
