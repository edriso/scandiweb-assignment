import PropTypes from 'prop-types';

function FormRow({
  name,
  labelText,
  type = 'text',
  defaultValue = '',
  required = false,
  onChange,
}) {
  return (
    <div className="form-row">
      <label htmlFor={name}>
        {labelText || name}
        {required && <span className="input-required"> *</span>}
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
  required: PropTypes.bool,
  onChange: PropTypes.func,
};

export default FormRow;
