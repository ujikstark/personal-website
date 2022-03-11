import React from 'react';
import { Button, Container, Nav, Navbar } from "react-bootstrap";
import { useAuth, useAuthUpdate } from '../contexts/AuthContext';
import SigninModal from './SigninModal';

function NavigationBar() {

    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const handleLogout = () => {
        updateAuth(false);
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
                                <Navbar.Text className="btn btn-link" as="span">
                                    Sign up
                                </Navbar.Text>
                            </Nav>
                        </>
                    }
                    
                </Navbar.Collapse>
            </Container>
      </Navbar>
    );
}


export default NavigationBar;