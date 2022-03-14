import React, { useContext, useEffect, useState } from "react";
import PropTypes from 'prop-types';
import refreshToken from "../requests/refreshToken";

const AuthContext = React.createContext();
const AuthUpdateContext = React.createContext();

export function useAuth () {
    return useContext(AuthContext);
}

export function useAuthUpdate () {
    return useContext(AuthUpdateContext);
}

export default function AuthProvider({ children }) {
    const [auth, setAuth] = useState(JSON.parse(localStorage.getItem('auth')));

    function updateAuth (newAuth) {
        setAuth(newAuth);
        localStorage.setItem('auth', JSON.stringify(newAuth));
    }

    useEffect( async () => {
        if (auth != null) await refreshToken(auth, updateAuth); 
    });
    

    return (
        <AuthContext.Provider value={auth}>
            <AuthUpdateContext.Provider value={updateAuth}>
                {children}
            </AuthUpdateContext.Provider>
        </AuthContext.Provider>
    );
}

AuthProvider.propTypes = {
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node
    ])
};