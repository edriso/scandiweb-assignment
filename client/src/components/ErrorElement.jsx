import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

function ErrorElement({ message }) {
  return (
    <div>
      <h4>{message || 'Something went wrong...'}</h4>
      <Link to="/" className="btn">
        Back home
      </Link>
    </div>
  );
}

ErrorElement.propTypes = {
  message: PropTypes.string,
};

export default ErrorElement;
