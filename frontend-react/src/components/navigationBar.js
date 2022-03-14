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
                <Link to="/">
                    <Navbar.Brand className="py-3">NePrint</Navbar.Brand>
                </Link>
                <Navbar.Toggle aria-controls="responsive-navbar-nav" />
                <Navbar.Collapse id="responsive-navbar-nav">
                    <Nav className="mr-auto">
                        <Link to="/todo">Todo List
                        </Link>
                    </Nav>
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