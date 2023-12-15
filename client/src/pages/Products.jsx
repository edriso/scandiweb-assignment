import PropTypes from 'prop-types';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { toast } from 'react-toastify';
import { HeaderLayout, SingleProduct } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

function Products({ queryClient }) {
  const { isLoading, isError, data } = useQuery({
    queryKey: ['products'],
    queryFn: async () => {
      const response = await apiHandler.get('/products');
      return response.data;
    },
  });

  const navigate = useNavigate();

  const [checkedProducts, setCheckedProducts] = useState([]);

  const handleCheckboxChange = (productId) => {
    setCheckedProducts((prevIds) => {
      if (prevIds.includes(productId)) {
        return prevIds.filter((id) => id !== productId);
      } else {
        return [...prevIds, productId];
      }
    });
  };

  const handleDelete = async () => {
    try {
      await apiHandler.delete('/products', {
        params: {
          productIds: checkedProducts.join(),
        },
      });
      setCheckedProducts([]);
      queryClient.invalidateQueries(['products']);
      return navigate('/');
    } catch (error) {
      toast.error(error?.response?.data?.message);
    }

    return navigate('/');
  };

  if (isLoading) {
    return <main>Loading...</main>;
  }

  if (isError) {
    return <main>Could not retrieve the products at the moment!</main>;
  }

  return (
    <main className="products">
      <HeaderLayout
        title="Product List"
        btnOneText="ADD"
        btnTwoText="MASS DELETE"
        btnOneAction={() => navigate('/add-product')}
        btnTwoAction={handleDelete}
        btnTwoId="delete-product-btn"
        btnTwoDisabled={checkedProducts.length === 0}
      />

      <section className="products__product-container">
        {data.data.products.length ? (
          data.data.products.map((product) => {
            return (
              <SingleProduct key={product.id} product={product}>
                <div className="products__checkbox-container">
                  <input
                    type="checkbox"
                    id={`product-${product.id}`}
                    className="delete-checkbox"
                    value={product.id}
                    onChange={() => handleCheckboxChange(product.id)}
                  />
                  <label htmlFor={`product-${product.id}`}>x</label>
                </div>
              </SingleProduct>
            );
          })
        ) : (
          <p>No products to show!</p>
        )}
      </section>
    </main>
  );
}

Products.propTypes = {
  queryClient: PropTypes.object,
};

export default Products;
