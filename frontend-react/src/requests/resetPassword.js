import axios from '../config/axios';

export async function resetPasswordEmail (email) {
    return await axios.post('/api/security/reset-password-email', JSON.stringify({ email: email}))
        .then(response => {
            return response.status == 200
        })
        .catch(() => {
            return false;
        })
}

export async function resetPassword (token, values) {
    const payload = {
        token: token,
        password: values.password,
        confirmPassword: values.confirmPassword
    }

    return await axios.post('/api/security/reset-password', JSON.stringify(payload))
        .then(response => {
            return response.data;
        })
        .catch(() => {
            return null;
        })
}