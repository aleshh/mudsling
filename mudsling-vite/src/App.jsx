import { Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import Layout from './components/Layout';
import ProtectedRoute from './components/ProtectedRoute';
import Loading from './components/Loading';
import Landing from './pages/Landing';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import ResetPassword from './pages/ResetPassword';
import Drink from './pages/Drink';
import History from './pages/History';
import BeveragesList from './pages/BeveragesList';
import BeverageForm from './pages/BeverageForm';
import BeverageDetail from './pages/BeverageDetail';
import Account from './pages/Account';
import About from './pages/About';
import NotFound from './pages/NotFound';

function AppRoutes() {
  const { user, loading } = useAuth();

  if (loading) return <Loading />;

  return (
    <Routes>
      <Route path="/" element={user ? <Navigate to="/drink" replace /> : <Landing />} />
      <Route path="/login" element={user ? <Navigate to="/drink" replace /> : <Login />} />
      <Route path="/register" element={user ? <Navigate to="/drink" replace /> : <Register />} />
      <Route path="/forgot-password" element={<ForgotPassword />} />
      <Route path="/reset-password" element={<ResetPassword />} />
      <Route path="/about" element={<About />} />

      <Route
        path="/drink"
        element={
          <ProtectedRoute>
            <Drink />
          </ProtectedRoute>
        }
      />
      <Route
        path="/history"
        element={
          <ProtectedRoute>
            <History />
          </ProtectedRoute>
        }
      />
      <Route
        path="/beverages"
        element={
          <ProtectedRoute>
            <BeveragesList />
          </ProtectedRoute>
        }
      />
      <Route
        path="/beverages/new"
        element={
          <ProtectedRoute>
            <BeverageForm />
          </ProtectedRoute>
        }
      />
      <Route
        path="/beverages/:id"
        element={
          <ProtectedRoute>
            <BeverageDetail />
          </ProtectedRoute>
        }
      />
      <Route
        path="/beverages/:id/edit"
        element={
          <ProtectedRoute>
            <BeverageForm />
          </ProtectedRoute>
        }
      />
      <Route
        path="/account"
        element={
          <ProtectedRoute>
            <Account />
          </ProtectedRoute>
        }
      />
      <Route path="*" element={<NotFound />} />
    </Routes>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <Layout>
        <AppRoutes />
      </Layout>
    </AuthProvider>
  );
}
