import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

function ErrorElement({ message }) {
  return (
    <main className="throw-error">
      <h3>{message || 'Something went wrong...'}</h3>
      <Link to="/" className="btn">
        Back home
      </Link>
    </main>
  );
}

ErrorElement.propTypes = {
  message: PropTypes.string,
};

export default ErrorElement;
