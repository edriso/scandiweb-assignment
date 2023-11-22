import PropTypes from 'prop-types';

function FormRow({
  type = 'text',
  name,
  defaultValue = '',
  labelText,
  onChange,
  isRequired,
}) {
  return (
    <div className="form-row">
      <label htmlFor={name}>{labelText || name}</label>
      <input
        type={type}
        id={name}
        name={name}
        defaultValue={defaultValue}
        required={isRequired}
        onChange={onChange}
      />
    </div>
  );
}

FormRow.propTypes = {
  type: PropTypes.string,
  name: PropTypes.string,
  defaultValue: PropTypes.string,
  labelText: PropTypes.string,
  onChange: PropTypes.func,
  isRequired: PropTypes.bool,
};

export default FormRow;
