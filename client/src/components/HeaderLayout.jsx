import PropTypes from 'prop-types';

function HeaderLayout({
  title,
  btnOneText,
  btnTwoText,
  btnOneAction,
  btnTwoAction,
  btnOneId,
  btnTwoId,
  btnOneClasses,
  btnTwoClasses,
  btnOneDisabled = false,
  btnTwoDisabled = false,
}) {
  return (
    <header className="header">
      <h3>{title}</h3>

      <div className="header__btn-container">
        <button
          onClick={btnOneAction}
          id={btnOneId}
          className={btnOneClasses}
          disabled={btnOneDisabled}
        >
          {btnOneText}
        </button>
        <button
          onClick={btnTwoAction}
          id={btnTwoId}
          className={btnTwoClasses}
          disabled={btnTwoDisabled}
        >
          {btnTwoText}
        </button>
      </div>
    </header>
  );
}

HeaderLayout.propTypes = {
  title: PropTypes.string,
  btnOneText: PropTypes.string,
  btnTwoText: PropTypes.string,
  btnOneAction: PropTypes.func,
  btnTwoAction: PropTypes.func,
  btnOneId: PropTypes.string,
  btnTwoId: PropTypes.string,
  btnOneClasses: PropTypes.string,
  btnTwoClasses: PropTypes.string,
  btnOneDisabled: PropTypes.bool,
  btnTwoDisabled: PropTypes.bool,
};

export default HeaderLayout;
