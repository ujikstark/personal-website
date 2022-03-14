import axios from "../config/axios";
import { addHours } from 'date-fns';


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

    auth.exp = addHours((new Date()), 1).getTime();
    auth.isAuthenticated = true;

    const user = await getMe(auth);

    return { auth, user }; 
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