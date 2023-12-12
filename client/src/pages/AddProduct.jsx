import { useRef, useState } from 'react';
import { useNavigate, useLoaderData } from 'react-router-dom';
import { toast } from 'react-toastify';
import { HeaderLayout, FormRow, FormRowSelect } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

export const loader = async () => {
  const { data } = await apiHandler.get('/types');
  return data;
};

function AddProduct() {
  const { data: productTypes } = useLoaderData();
  const navigate = useNavigate();
  const formRef = useRef();

  const [selectedType, setSelectedType] = useState({});
  const [selectedTypeProperties, setSelectedTypeProperties] = useState([]);
  const [properties, setProperties] = useState({});

  const handleTypeChange = async (typeId) => {
    const selectedProductType = productTypes.find(
      (type) => type.id === Number(typeId)
    );
    setSelectedType(selectedProductType);

    try {
      const response = await apiHandler.get(`/properties?type_id=${typeId}`);
      setSelectedTypeProperties(response.data.data);
    } catch (error) {
      toast.error(error?.response?.data?.message);
    }
  };

  const validateProduct = (product) => {
    if (!product.sku || !product.name || !product.price || !product.type_id) {
      toast.error('Please, submit required data');
      return false;
    }

    if (isNaN(product.price)) {
      toast.error('Please, provide the data of indicated type');
      return false;
    }

    return true;
  };

  const handleSubmit = async () => {
    const formData = new FormData(formRef.current);
    const productData = {
      ...Object.fromEntries(formData),
      ['properties']: properties,
    };

    if (!validateProduct(productData)) return;

    try {
      await apiHandler.post('/products', JSON.stringify(productData));
      return navigate('/');
    } catch (error) {
      if (error?.response?.data?.message.includes('properties must include')) {
        return toast.error(`Please, provide ${selectedType.measure_name}`);
      }
      if (error?.response?.data?.message.includes('Invalid data type')) {
        return toast.error('Please, provide the data of indicated type');
      }
      toast.error(error?.response?.data?.message);
    }
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
          name="type_id"
          placeholder="Please Select Type"
          list={productTypes}
          onChange={(e) => handleTypeChange(e.target.value)}
        />
      </form>

      {!!selectedTypeProperties.length && (
        <div id={selectedType.name} className="product-type-container">
          {selectedTypeProperties.map((property) => {
            return (
              <FormRow
                key={property.id}
                name={property.name}
                labelText={`${property.name} (${property.unit})`}
                onChange={(e) =>
                  setProperties({
                    ...properties,
                    [property.name]: e.target.value,
                  })
                }
              />
            );
          })}
          <p>
            Please provide{' '}
            <span className="text-capitalize">{selectedType.measure_name}</span>{' '}
            in{' '}
            <span className="text-capitalize">{selectedType.measure_unit}</span>{' '}
            format
          </p>
        </div>
      )}
    </main>
  );
}

export default AddProduct;
