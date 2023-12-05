import PropTypes from 'prop-types';

function ErrorElement({ message }) {
  return <h4>{message || 'Something went wrong...'}</h4>;
}

ErrorElement.propTypes = {
  message: PropTypes.string,
};

export default ErrorElement;
