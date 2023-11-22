import { Link, useRouteError } from 'react-router-dom';
import notFound from '../assets/not-found.svg';

function Error() {
  const error = useRouteError();

  if (error.status === 404) {
    return (
      <main className="not-found">
        <img src={notFound} alt="not found" />
        <h2>Page not found</h2>
        <Link to="/" className="btn">
          Back home
        </Link>
      </main>
    );
  }

  return (
    <main>
      <h3>Something went wrong</h3>
    </main>
  );
}
export default Error;
