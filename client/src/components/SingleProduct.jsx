import PropTypes from 'prop-types';

function SingleProduct({ product, children }) {
  //   console.log(product);
  return (
    <div className="products__single-product fade-in">
      {children}
      <p>{product.sku}</p>
      <p>{product.name}</p>
      <p>{product.price} $</p>
    </div>
  );
}

SingleProduct.propTypes = {
  product: PropTypes.object,
  children: PropTypes.element,
};

export default SingleProduct;
