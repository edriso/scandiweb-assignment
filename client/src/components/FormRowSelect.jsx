import PropTypes from 'prop-types';

function FormRowSelect({
  name,
  labelText,
  placeholder,
  id,
  defaultValue = '',
  list = [],
  required = false,
  onChange,
}) {
  return (
    <div className="form-row">
      <label htmlFor={id || name}>
        {labelText || name}
        {required && <span className="input-required"> *</span>}
      </label>
      <select
        className="text-capitalize"
        name={name}
        id={id || name}
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
  id: PropTypes.string,
  list: PropTypes.array,
  required: PropTypes.bool,
  onChange: PropTypes.func,
};

export default FormRowSelect;
