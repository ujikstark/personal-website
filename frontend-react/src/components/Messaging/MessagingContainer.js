import React from "react";
import { Container } from "react-bootstrap";
import { useAuth } from "../../contexts/AuthContext";
import SigninModal from "../SigninModal";
import SignupModal from "../SignupModal";

function MessagingContainer () {
    const auth = useAuth();

    if (auth === null) {
        return <Container className="mt-4 shadow border rounded">
            <div className="m-4">
                <div className="mb-2">Sign up or sign in to use the app.</div>
            </div>
            <div className="m-4">
                <SigninModal/>
                <SignupModal/>
            </div>
        </Container>
    }
}

export default MessagingContainer;