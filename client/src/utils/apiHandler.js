import axios from 'axios';

export const apiHandler = axios.create({
  baseURL: '/api/v1',
});
