import PropTypes from 'prop-types';

function SingleProduct({ product, children }) {
  return (
    <div className="single-product fade-in">
      {children}
      <p>{product.sku}</p>
      <p>{product.name}</p>
      <p>{product.price} $</p>
      <p>{product.description}</p>
    </div>
  );
}

SingleProduct.propTypes = {
  product: PropTypes.object,
  children: PropTypes.element,
};

export default SingleProduct;
