import PropTypes from 'prop-types';

function SingleProduct({ product, children }) {
  console.log(product);
  return (
    <section className="single-product fade-in">
      {children}
      <div className="flex flex-jc-sb">
        <p>{product.name}</p>
        <p className="single-product__price">
          <span>$</span>
          {product.price}
        </p>
      </div>
      <div className="single-product__description">
        <span className="text-capitalize">{product.type}</span>{' '}
        {product.description}
        <p>
          <small>SKU: {product.sku}</small>
        </p>
      </div>
    </section>
  );
}

SingleProduct.propTypes = {
  product: PropTypes.object,
  children: PropTypes.element,
};

export default SingleProduct;
