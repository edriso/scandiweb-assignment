import PropTypes from 'prop-types';

function FormRowSelect({
  name,
  labelText,
  defaultValue = '',
  placeholder,
  list,
  onChange,
}) {
  return (
    <div className="form-row">
      <label htmlFor={name}>{labelText || name}</label>
      <select
        name={name}
        id={name}
        defaultValue={defaultValue || placeholder}
        onChange={onChange}
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
};

export default FormRowSelect;
