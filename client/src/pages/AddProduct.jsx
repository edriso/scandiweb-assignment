import { useRef, useState } from 'react';
import { useNavigate, useLoaderData } from 'react-router-dom';
import { HeaderLayout, FormRow, FormRowSelect } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

export const loader = async () => {
  const { data } = await apiHandler.get('/product-types');
  return data;
};

function AddProduct() {
  const { data: productTypes } = useLoaderData();
  const navigate = useNavigate();
  const formRef = useRef();

  const [selectedType, setSelectedType] = useState({});
  const [selectedTypeProperties, setSelectedTypeProperties] = useState([]);

  const handleTypeChange = async (typeId) => {
    const selectedProductType = productTypes.find(
      (type) => type.id === Number(typeId)
    );
    setSelectedType(selectedProductType);
    const response = await apiHandler.get(`/type-properties?type_id=${typeId}`);
    if (response.status === 200) {
      setSelectedTypeProperties(response.data.data);
    }
  };

  const handleSubmit = () => {
    const formData = new FormData(formRef.current);
    const newProduct = Object.fromEntries(formData);
    console.log(newProduct);
    alert('add new product');
  };

  return (
    <main className="add-product">
      <HeaderLayout
        title="Product Add"
        btnOneText="Save"
        btnTwoText="Cancel"
        btnOneAction={handleSubmit}
        btnTwoAction={() => navigate('/')}
      />

      <form method="POST" id="product_form" className="fade-in" ref={formRef}>
        <FormRow name="sku" labelText="SKU" />
        <FormRow name="name" />
        <FormRow type="number" labelText="Price ($)" name="price" />
        <FormRowSelect
          labelText="Type switcher"
          name="productType"
          placeholder="Please Select Type"
          list={productTypes}
          onChange={(e) => handleTypeChange(e.target.value)}
        />

        {!!selectedTypeProperties.length && (
          <div id={selectedType.name} className="product-type-container">
            {selectedTypeProperties.map((property) => {
              return (
                <FormRow
                  key={property.id}
                  name={property.name}
                  labelText={`${property.name} (${property.unit})`}
                />
              );
            })}
            <p>
              Please provide{' '}
              <span className="text-capitalize">
                {selectedType.measure_name}
              </span>{' '}
              in{' '}
              <span className="text-capitalize">
                {selectedType.measure_unit}
              </span>{' '}
              format
            </p>
          </div>
        )}
      </form>
    </main>
  );
}

export default AddProduct;
