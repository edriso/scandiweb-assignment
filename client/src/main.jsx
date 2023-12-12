import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App.jsx';
import './assets/scss/main.scss';
import 'react-toastify/dist/ReactToastify.css';
import { Slide, ToastContainer } from 'react-toastify';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
    <ToastContainer position="top-right" transition={Slide} autoClose={2000} />
  </React.StrictMode>
);
