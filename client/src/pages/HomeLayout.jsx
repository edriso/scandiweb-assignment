import { Outlet } from 'react-router-dom';
import { FooterLayout } from '../components';

function HomeLayout() {
  return (
    <>
      <Outlet />
      <FooterLayout />
    </>
  );
}
export default HomeLayout;
