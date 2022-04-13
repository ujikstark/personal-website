import React from 'react';
import { Container, Nav, Navbar } from "react-bootstrap";
import { useAuth, useAuthUpdate } from '../contexts/AuthContext';
import { logout } from '../requests/user';
import SigninModal from './SigninModal';
import SignupModal from './SignupModal';    
import { Link } from 'react-router-dom';


function NavigationBar() {

    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const handleLogout = () => {
        logout(updateAuth);
    }

    return (
        <Navbar fixed="top" bg="dark" variant="dark" expand="lg" className={'sticky-top'}>
            <Container>
                <Link to="/" style={{ textDecoration: 'none' }}>
                    <Navbar.Brand className="py-3">NePrint</Navbar.Brand>
                </Link>
                <Navbar.Toggle aria-controls="responsive-navbar-nav" />
                <Navbar.Collapse id="responsive-navbar-nav">
                    <Nav className="mr-auto">
                        <Link to="/todo" style={{ textDecoration: 'none' }}>
                            <Nav.Link as ="span">Todo</Nav.Link>
                        </Link>
                        <Link to="/messaging" style={{ textDecoration: 'none' }}>
                            <Nav.Link as ="span">Message</Nav.Link>
                        </Link>
                    </Nav>
                    {auth 
                        ? <>
                            <Nav className="ms-auto">
                                <Link to="/me">
                                    <Navbar.Text className="btn btn-link" as="span">
                                        My profile
                                    </Navbar.Text>
                                </Link>
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