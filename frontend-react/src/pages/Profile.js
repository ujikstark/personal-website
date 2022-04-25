import React, { useEffect } from 'react'
import { useAuth } from "../contexts/AuthContext";
import { useNavigate } from 'react-router-dom';
import { Card, Container } from "react-bootstrap";
import { Helmet } from "react-helmet";
import UpdatePasswordModal from '../components/UpdatePasswordModal';

function Profile () {
    const auth = useAuth();
    let navigate = useNavigate();

    useEffect(() => {
        if (auth === null) {
            navigate("/", { replace: true });
        }
    }, [auth]);


    return (
        <Container>
            <Helmet><title>My Profile</title></Helmet>
            <Card className="m-2 p-3 shadow">
                <Card.Body>
                    <Card.Title className="mb-4">Account's setttings</Card.Title>
                    <UpdatePasswordModal/>
                </Card.Body>
            </Card>
        </Container>
    );
}

export default Profile;