import { Link } from 'react-router-dom';

export default function Landing() {
  return (
    <div>
      <h2>Welcome to Mudsling</h2>
      <p>
        Add beverages and track when you&apos;ve consumed them to log your daily alcohol intake. Cheers!
      </p>
      <p>
        There&apos;s a couple of nice features, like a graph that shows your consumption relative to a
        daily goal you can set. Stuff gets saved to a database so you can log in from any machine.
      </p>
      <p>
        <Link to="/register" className="btn">Register</Link>
        <Link to="/login" className="btn btn-secondary">Login</Link>
      </p>

      <h3>Warnings</h3>
      <p>
        This is a toy app I&apos;m messing around with to learn Laravel, so it&apos;s just for fun and/or
        alpha testing—your data is likely to get deleted sooner or later.
      </p>
      <p>
        There&apos;s lots of stuff missing; check out the original GitHub repo to see what&apos;s on the
        TODO list.
      </p>
      <p>Ugly on purpose.</p>
    </div>
  );
}
