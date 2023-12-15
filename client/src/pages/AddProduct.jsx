import PropTypes from 'prop-types';
import { useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { toast } from 'react-toastify';
import { HeaderLayout, FormRow, FormRowSelect } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

const typesQuery = {
  queryKey: ['types'],
  queryFn: async () => {
    const { data } = await apiHandler.get('/types');
    return data.data;
  },
};

const propertiesQuery = (typeId) => {
  return {
    queryKey: ['properties', typeId ?? ''],
    queryFn: async () => {
      const { data } = await apiHandler.get('/properties', {
        params: { type_id: typeId },
      });
      return data;
    },
  };
};

export const loader = (queryClient) => async () => {
  await queryClient.ensureQueryData(typesQuery);
  return null;
};

function AddProduct({ queryClient }) {
  const { data: productTypes } = useQuery(typesQuery);
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
    setProperties([]);

    try {
      const { data } = await queryClient.fetchQuery(propertiesQuery(typeId));
      setSelectedTypeProperties(data);
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

  const handleSubmit = async (queryClient) => {
    const formData = new FormData(formRef.current);
    const productData = {
      ...Object.fromEntries(formData),
      ['properties']: properties,
    };

    if (!validateProduct(productData)) return;

    try {
      await apiHandler.post('/products', JSON.stringify(productData));
      queryClient.invalidateQueries(['products']);
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
        btnTwoClasses="btn-cancel"
        btnOneAction={() => handleSubmit(queryClient)}
        btnTwoAction={() => navigate('/')}
      />

      <form method="POST" id="product_form" className="fade-in" ref={formRef}>
        <FormRow name="sku" labelText="SKU" required />
        <FormRow name="name" required />
        <FormRow type="number" labelText="Price ($)" name="price" required />
        <FormRowSelect
          labelText="Type switcher"
          name="type_id"
          id="productType"
          placeholder="Please Select Type"
          list={productTypes}
          onChange={(e) => handleTypeChange(e.target.value)}
          required
        />
      </form>

      {!!selectedTypeProperties.length && (
        <div
          id={
            selectedType.name.charAt(0).toUpperCase() +
            selectedType.name.slice(1)
          }
          className="product-type-container"
        >
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
                required
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

AddProduct.propTypes = {
  queryClient: PropTypes.object,
};

export default AddProduct;
