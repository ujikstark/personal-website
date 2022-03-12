import axios from 'axios';

const instance = axios.create({
    baseUrl: 'http://localhost/',
    withCredentials: true,
    headers: {
        'Content-Type': true,
        Accept: 'application/json'
    }
})