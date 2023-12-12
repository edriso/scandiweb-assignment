import PropTypes from 'prop-types';

function FormRow({
  type = 'text',
  name,
  defaultValue = '',
  labelText,
  onChange,
  required,
}) {
  return (
    <div className="form-row">
      <label htmlFor={name}>
        {labelText || name}{' '}
        {required && <span className="input-required">*</span>}
      </label>
      <input
        type={type}
        id={name}
        name={name}
        defaultValue={defaultValue}
        required={required}
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
  required: PropTypes.bool,
};

export default FormRow;
