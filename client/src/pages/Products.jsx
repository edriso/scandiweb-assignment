import PropTypes from 'prop-types';
import { useNavigate } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { toast } from 'react-toastify';
import { HeaderLayout, SingleProduct } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

const productsQuery = {
  queryKey: ['products'],
  queryFn: async () => {
    const { data } = await apiHandler.get('/products');
    return data.data;
  },
};

export const loader = (queryClient) => async () => {
  await queryClient.ensureQueryData(productsQuery);
  return null;
};

function Products({ queryClient }) {
  const {
    data: { products },
  } = useQuery(productsQuery);

  const navigate = useNavigate();

  const handleDelete = async () => {
    const checkedProducts = [];
    const checkboxes = document.querySelectorAll(
      'input.delete-checkbox:checked'
    );

    for (const checkbox of checkboxes) {
      checkedProducts.push(checkbox.value);
    }

    try {
      await apiHandler.get('/products-delete', {
        params: {
          productIds: checkedProducts.join(),
        },
      });
      queryClient.invalidateQueries(['products']);
    } catch (error) {
      toast.error(error?.response?.data?.message);
    }
  };

  return (
    <main className="products">
      <HeaderLayout
        title="Product List"
        btnOneText="ADD"
        btnTwoText="MASS DELETE"
        btnOneAction={() => navigate('/add-product')}
        btnTwoAction={handleDelete}
        btnTwoId="delete-product-btn"
      />

      <section className="products__product-container">
        {products.length ? (
          products.map((product) => {
            return (
              <SingleProduct key={product.id} product={product}>
                <div className="products__checkbox-container">
                  <input
                    type="checkbox"
                    id={`product-${product.id}`}
                    className="delete-checkbox"
                    value={product.id}
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
