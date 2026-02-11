import { Link } from 'react-router-dom';

export default function NotFound() {
  return (
    <div>
      <h2>Page not found</h2>
      <p>
        Try heading back to <Link to="/">home</Link>.
      </p>
    </div>
  );
}
