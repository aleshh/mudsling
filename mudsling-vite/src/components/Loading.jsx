export default function Loading({ message = 'Loading...' }) {
  return (
    <div className="loading">
      <p>{message}</p>
    </div>
  );
}
