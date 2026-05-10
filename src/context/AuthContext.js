import { createContext, useContext, useState, useCallback } from 'react';
import { adminApi } from '../services/adminApi';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [authState, setAuthState] = useState({
    isAuthenticated: false,
    adminId: null,
    adminName: null,
    adminRole: null,
  });

  const login = useCallback(async (credentials) => {
    const response = await adminApi.login(credentials);
    if (response.success && response.admin) {
      setAuthState({
        isAuthenticated: true,
        adminId: response.admin.id,
        adminName: response.admin.name,
        adminRole: response.admin.role,
      });
    }
    return response;
  }, []);

  const logout = useCallback(async () => {
    try {
      await adminApi.logout();
    } finally {
      setAuthState({
        isAuthenticated: false,
        adminId: null,
        adminName: null,
        adminRole: null,
      });
    }
  }, []);

  return (
    <AuthContext.Provider value={{ authState, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
}
