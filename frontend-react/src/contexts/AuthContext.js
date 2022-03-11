import React, { useContext, useState } from "react";

const AuthContext = React.createContext();
const AuthUpdateContext = React.createContext();

export function useAuth () {
    return useContext(AuthContext);
}

export function useAuthUpdate () {
    return useContext(AuthUpdateContext);
}

export default function AuthProvider({ children }) {
    const [auth, setAuth] = useState(false);

    function updateAuth (newAuth) {
        setAuth(newAuth);
    }

    return (
        <AuthContext.Provider value={auth}>
            <AuthUpdateContext.Provider value={updateAuth}>
                {children}
            </AuthUpdateContext.Provider>
        </AuthContext.Provider>
    );
}
