import axios from 'axios';

const api = axios.create({
    baseURL: '/v1',
    headers: {
        'Accept': 'application/json',
    },
});

export default api;
