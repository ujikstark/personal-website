import axios from "../config/axios";
import { addHours, addMinutes } from 'date-fns';
import refreshToken from "./refreshToken";


export async function signinSubmit (values) {
    const payload = {
        email: values.email,
        password: values.password
    };

    const response = await axios.post('/security/login', JSON.stringify(payload))
        .then(response => {
            // return empty string
            return response.data;
        })
        .catch(() => null);
    
    const auth = {};
    
    if (response === null) {
        auth.isAuthenticated = false;

        return { auth, user: null };
    }

    auth.exp = addMinutes((new Date()), 2).getTime();
    auth.isAuthenticated = true;

    const user = await getMe(auth);

    return { auth, user }; 
}

export async function signupSubmit (values) {
    const payload = {
        email: values.email,
        name: values.name,
        password: values.password,
    }

    return await axios.post('/api/users', JSON.stringify(payload))
        .then(() => true)
        .catch(() => false);
}

export async function updatePasswordSubmit (values, auth, updateAuth) {
    const payload = {
        currentPassword: values.currentPassword,
        newPassword: values.newPassword,
        confirmPassword: values.confirmPassword
    };

    await refreshToken(auth, updateAuth);

    return await axios.post('/api/account/update-password', JSON.stringify(payload))
        .then(response => {
            return response.status === 200;
        })
        .catch(() => {
            return false;
        })
}

export async function getUser (id, auth, updateAuth) {
    await refreshToken(auth, updateAuth);

    return await axios.get('/api/users/' + id)
        .then(response => response.data)
        .catch(() => null);
}

export async function getMe () {
    const user = await axios.get('/api/account/me')
        .then(response => {
            return response.data;
        });

    localStorage.setItem('user', JSON.stringify(user));

    return user;
}

export async function logout (updateAuth) {
    updateAuth(null);
    localStorage.removeItem('user');

    await axios.post('/security/logout');
}