import { useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { HeaderLayout, FormRow, FormRowSelect } from '../components';

function AddProduct() {
  const navigate = useNavigate();
  const formRef = useRef();

  const [productTypes, setProductTypes] = useState([]);

  const [selectedType, setSelectedType] = useState({});
  const [selectedTypeProperties, setSelectedTypeProperties] = useState([]);

  const handleTypeChange = (typeId) => {
    const selectedProductType = productTypes.find(
      (type) => type.id === Number(typeId)
    );
    setSelectedType(selectedProductType);

    setSelectedTypeProperties([
      {
        id: 1,
        type_id: 3,
        name: 'height',
        unit: 'cm',
      },
      {
        id: 2,
        type_id: 3,
        name: 'width',
        unit: 'cm',
      },
      {
        id: 3,
        type_id: 3,
        name: 'length',
        unit: 'cm',
      },
    ]);
  };

  const handleSubmit = () => {
    const formData = new FormData(formRef.current);
    const newProduct = Object.fromEntries(formData);
    console.log(newProduct);
    alert('add new product');
  };

  useEffect(() => {
    setProductTypes([
      {
        id: 1,
        name: 'DVD',
        measure_name: 'size',
        measure_unit: 'MB',
      },
      {
        id: 2,
        name: 'book',
        measure_name: 'weight',
        measure_unit: 'kg',
      },
      {
        id: 3,
        name: 'furniture',
        measure_name: 'dimensions',
        measure_unit: 'HxWxL',
      },
    ]);
  }, []);

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
