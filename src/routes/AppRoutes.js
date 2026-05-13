import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Public Pages
import { HomePage } from '../pages/HomePage';
import { ServicesPage } from '../pages/ServicesPage';
import { Step1Page } from '../pages/order/Step1Page';
import { Step2Page } from '../pages/order/Step2Page';
import { Step3Page } from '../pages/order/Step3Page';
import { SuccessPage } from '../pages/order/SuccessPage';
import { StatusFormPage } from '../pages/status/StatusFormPage';
import { StatusResultPage } from '../pages/status/StatusResultPage';

// Admin Pages
import { AdminLoginPage } from '../pages/admin/AdminLoginPage';
import { AdminDashboardPage } from '../pages/admin/AdminDashboardPage';
import { AdminCompletedPage } from '../pages/admin/AdminCompletedPage';
import { AdminServicesPage } from '../pages/admin/AdminServicesPage';
import { AdminServiceFormPage } from '../pages/admin/AdminServiceFormPage';
import { AdminUsersPage } from '../pages/admin/AdminUsersPage';
import { AdminEditUserPage } from '../pages/admin/AdminEditUserPage';
import { AdminCreateFormPage } from '../pages/admin/AdminCreateFormPage';
import { AdminCmsPage } from '../pages/admin/AdminCmsPage';

function AdminGuard({ children, requireSuperadmin = false }) {
  const { authState } = useAuth();

  if (!authState.isAuthenticated) {
    return <Navigate to="/admin/login" replace />;
  }

  if (requireSuperadmin && authState.adminRole !== 'superadmin') {
    return <Navigate to="/admin/dashboard" replace />;
  }

  return children;
}

export function AppRoutes() {
  return (
    <Routes>
      {/* Public Routes */}
      <Route path="/" element={<HomePage />} />
      <Route path="/layanan" element={<ServicesPage />} />
      <Route path="/pesan/step-1" element={<Step1Page />} />
      <Route path="/pesan/step-2" element={<Step2Page />} />
      <Route path="/pesan/step-3" element={<Step3Page />} />
      <Route path="/pesan/selesai" element={<SuccessPage />} />
      <Route path="/cek-status" element={<StatusFormPage />} />
      <Route path="/cek-status/result/:id" element={<StatusResultPage />} />

      {/* Admin Routes */}
      <Route path="/admin/login" element={<AdminLoginPage />} />
      <Route
        path="/admin/dashboard"
        element={
          <AdminGuard>
            <AdminDashboardPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/completed"
        element={
          <AdminGuard>
            <AdminCompletedPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/services"
        element={
          <AdminGuard requireSuperadmin>
            <AdminServicesPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/services/create"
        element={
          <AdminGuard requireSuperadmin>
            <AdminServiceFormPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/services/:id/edit"
        element={
          <AdminGuard requireSuperadmin>
            <AdminServiceFormPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/admins"
        element={
          <AdminGuard requireSuperadmin>
            <AdminUsersPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/admins/create"
        element={
          <AdminGuard requireSuperadmin>
            <AdminCreateFormPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/admins/:id/edit"
        element={
          <AdminGuard requireSuperadmin>
            <AdminEditUserPage />
          </AdminGuard>
        }
      />
      <Route
        path="/admin/cms"
        element={
          <AdminGuard requireSuperadmin>
            <AdminCmsPage />
          </AdminGuard>
        }
      />

      {/* Catch all */}
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}
