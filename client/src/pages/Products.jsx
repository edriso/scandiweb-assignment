import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { HeaderLayout, SingleProduct } from '../components';
import { products } from '../utils/products';

function Products() {
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

  const handleDelete = () => {
    console.log('Selected Product IDs:', checkedProducts);
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
        {products.map((product) => {
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
        })}
      </section>
    </main>
  );
}

export default Products;
