import React from 'react';
import { Button, Container, Nav, Navbar } from "react-bootstrap";
import { useAuth, useAuthUpdate } from '../contexts/AuthContext';
import { logout } from '../requests/user';
import SigninModal from './SigninModal';
import SignupModal from './SignupModal';

function NavigationBar() {

    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const handleLogout = () => {
        logout(updateAuth);
    }

    return (
        <Navbar fixed="top" bg="light" expand="lg" className={'sticky-top'}>
            <Container>
                <Navbar.Brand className="py-3">NePrint</Navbar.Brand>
                <Navbar.Toggle aria-controls="responsive-navbar-nav" />
                <Navbar.Collapse id="responsive-navbar-nav">
                    {auth 
                        ? <>
                            <Nav className="ms-auto">
                                <Navbar.Text>berhasil login</Navbar.Text>
                                <Navbar.Text onClick={handleLogout} className="btn btn-link">Logout</Navbar.Text>
                            </Nav>
                        </>
                        : <>
                            <Nav className="ms-auto">
                                <SigninModal/>
                                <SignupModal/>
                            </Nav>
                        </>
                    }
                    
                </Navbar.Collapse>
            </Container>
      </Navbar>
    );
}


export default NavigationBar;