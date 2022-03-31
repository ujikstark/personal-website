import axios from '../config/axios';
import refreshToken from './refreshToken';

export async function getConversations (auth, updateAuth) {
    await refreshToken(auth, updateAuth);

    return await axios.get('/api/conversations')
        .then(response => response.data)
        .catch(() => []);
}

export async function getConversation (id, auth, updateAuth) {
    await refreshToken(auth, updateAuth);

    return await axios.get('/api/conversations/' + id)
        .then(response => response.data)
        .catch(() => null);
}
