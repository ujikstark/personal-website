import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost/',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json'
    }
});

export default instance;
