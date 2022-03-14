import { addHours, addMinutes } from "date-fns";
import axios from "../config/axios";
import { logout } from "./user";

export default async function refreshToken (auth, updateAuth) {
    if (addMinutes((new Date()), 1).getTime() < auth.exp) {
        return;
    }

    const isTokenRefreshed = await axios.post('/security/refresh-token')
        .then((data) => {
            console.log(data);
            return true;

        })
        .catch(() => false);

    if (isTokenRefreshed === false) {
        logout(updateAuth);

        return;
    }

    auth.exp = addHours((new Date()), 1).getTime();
    updateAuth(auth);
}