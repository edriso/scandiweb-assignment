import { useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { HeaderLayout, FormRow, FormRowSelect } from '../components';

function AddProduct() {
  const navigate = useNavigate();
  const formRef = useRef();

  const handleTypeChange = (type) => {
    console.log(type);
  };

  const handleSubmit = () => {
    const formData = new FormData(formRef.current);
    const newProduct = Object.fromEntries(formData);
    console.log(newProduct);
    alert('add new product');
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

      <form id="product_form" className="fade-in" ref={formRef}>
        <FormRow name="sku" labelText="SKU" />
        <FormRow name="name" />
        <FormRow type="number" labelText="Price ($)" name="price" />
        <FormRowSelect
          labelText="Type switcher"
          name="productType"
          list={['Type Switcher', '1', '2', '3']}
          onChange={(e) => handleTypeChange(e.target.value)}
        />

        {/* TEMPORARY */}
        <hr />
        <div id="DVD" className="product-type-container">
          <FormRow name="size" labelText="Size (MB)" />
          <p>Please provide size in MB format</p>
        </div>
        <div id="Furniture" className="product-type-container">
          <FormRow name="height" labelText="Height (CM)" />
          <FormRow name="width" labelText="Width (CM)" />
          <FormRow name="length" labelText="Length (CM)" />
          <p>Please provide dimensions in HxWxL format</p>
        </div>
        <div id="Book" className="product-type-container">
          <FormRow name="weight" labelText="Weight (KG)" />
          <p>Please provide weight in KG format</p>
        </div>
      </form>
    </main>
  );
}

export default AddProduct;
