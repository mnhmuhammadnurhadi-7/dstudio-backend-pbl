import axios from 'axios';

axios.defaults.baseURL = process.env.REACT_APP_API_URL ?? 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';

export default axios;
