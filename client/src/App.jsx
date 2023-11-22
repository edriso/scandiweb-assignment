import { RouterProvider, createBrowserRouter } from 'react-router-dom';
import { Error, HomeLayout, Products, AddProduct } from './pages';
import { ErrorElement } from './components';

const router = createBrowserRouter([
  {
    path: '/',
    element: <HomeLayout />,
    errorElement: <Error />,
    children: [
      {
        index: true,
        element: <Products />,
        errorElement: <ErrorElement />,
        // loader: productsLoader(queryClient),
      },
      {
        path: 'add-product',
        element: <AddProduct />,
        errorElement: <ErrorElement />,
        // action: addProductAction,
      },
    ],
  },
]);

function App() {
  return (
    <>
      <RouterProvider router={router} />
    </>
  );
}

export default App;
