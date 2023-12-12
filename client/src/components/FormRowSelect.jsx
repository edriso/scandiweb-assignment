import PropTypes from 'prop-types';

function FormRowSelect({
  name,
  labelText,
  placeholder,
  defaultValue = '',
  list = [],
  onChange,
  required = false,
}) {
  return (
    <div className="form-row">
      <label htmlFor={name}>
        {labelText || name}
        {required && <span className="input-required"> *</span>}
      </label>
      <select
        className="text-capitalize"
        name={name}
        id={name}
        defaultValue={defaultValue || placeholder}
        onChange={onChange}
        required={required}
      >
        {placeholder && <option disabled>{placeholder}</option>}
        {list.map((item) => {
          return (
            <option key={item.id || item} value={item.id || item}>
              {item.name || item}
            </option>
          );
        })}
      </select>
    </div>
  );
}

FormRowSelect.propTypes = {
  name: PropTypes.string,
  defaultValue: PropTypes.string,
  labelText: PropTypes.string,
  placeholder: PropTypes.string,
  list: PropTypes.array,
  onChange: PropTypes.func,
  required: PropTypes.bool,
};

export default FormRowSelect;
