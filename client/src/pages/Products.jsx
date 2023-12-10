import { useState } from 'react';
import { useNavigate, useLoaderData } from 'react-router-dom';
import { HeaderLayout, SingleProduct } from '../components';
import { apiHandler } from '../utils/apiHandler.js';

export const loader = async () => {
  const { data } = await apiHandler.get('/products');
  return data;
};

function Products() {
  const navigate = useNavigate();
  const {
    data: { products },
  } = useLoaderData();

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
      await apiHandler.delete(`/products?productIds=${checkedProducts.join()}`);
      setCheckedProducts([]);
    } catch (error) {
      console.log('Something went wrong'); // TEMPORARY
    }

    return navigate('/');
  };

  return (
    <main className="products">
      <HeaderLayout
        title="Product List"
        btnOneText="Add"
        btnTwoText="Mass Delete"
        btnOneAction={() => navigate('/add-product')}
        btnTwoAction={handleDelete}
        btnTwoId="delete-product-btn"
        btnTwoDisabled={checkedProducts.length === 0}
      />

      <section className="products__product-container">
        {products.length ? (
          products.map((product) => {
            return (
              <SingleProduct key={product.id} product={product}>
                <input
                  type="checkbox"
                  name="delete-checkbox"
                  value={product.id}
                  onChange={() => handleCheckboxChange(product.id)}
                />
              </SingleProduct>
            );
          })
        ) : (
          <p>No products to show</p>
        )}
      </section>
    </main>
  );
}

export default Products;
