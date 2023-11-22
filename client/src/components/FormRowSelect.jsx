import PropTypes from 'prop-types';

function FormRowSelect({ name, labelText, defaultValue = '', list, onChange }) {
  return (
    <div className="form-row">
      <label htmlFor={name}>{labelText || name}</label>
      <select
        name={name}
        id={name}
        defaultValue={defaultValue}
        onChange={onChange}
      >
        {list.map((item) => {
          return (
            <option key={item} value={item}>
              {item}
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
  onChange: PropTypes.func,
  list: PropTypes.array,
};

export default FormRowSelect;
