import PropTypes from 'prop-types';

function HeaderLayout({
  title,
  btnOneText,
  btnTwoText,
  btnOneAction,
  btnTwoAction,
  btnTwoId,
}) {
  return (
    <header className="header">
      <h3>{title}</h3>

      <div className="header__btn-container">
        <button onClick={btnOneAction}>{btnOneText}</button>
        <button onClick={btnTwoAction} id={btnTwoId}>
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
  btnTwoId: PropTypes.string,
};

export default HeaderLayout;
