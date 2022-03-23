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