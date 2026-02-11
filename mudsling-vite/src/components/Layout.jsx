import { NavLink, Link } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import ConsumptionStatus from './ConsumptionStatus';

export default function Layout({ children }) {
  const { user } = useAuth();
  const navClass = ({ isActive }) => (isActive ? 'active' : undefined);

  return (
    <div className="app-shell">
      <div className="cragle" />
      <header>
        <div className="container">
          <nav>
            <div className="cannon">
              <Link to="/">
                <img src="/images/pint-glass-transparent.png" alt="Mudsling" />
              </Link>
            </div>
            {user ? (
              <>
                <NavLink to="/drink" className={navClass}>Drink!</NavLink>
                <NavLink to="/history" className={navClass}>History</NavLink>
                <NavLink to="/account" className={navClass}>Account</NavLink>
              </>
            ) : (
              <>
                <NavLink to="/register" className={navClass}>Register</NavLink>
                <NavLink to="/login" className={navClass}>Login</NavLink>
              </>
            )}
          </nav>
        </div>
      </header>

      <main>
        <div className="container">{children}</div>
      </main>

      {user && <ConsumptionStatus />}
    </div>
  );
}
