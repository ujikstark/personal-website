import React from 'react';
import { Button, Container, Nav, Navbar } from "react-bootstrap";
import SigninModal from './SigninModal';

function NavigationBar() {
    return (
        <Navbar fixed="top" bg="light" expand="lg" className={'sticky-top'}>
            <Container>
                <Navbar.Brand className="py-3">NePrint</Navbar.Brand>
                <Navbar.Toggle aria-controls="responsive-navbar-nav" />
                <Navbar.Collapse id="responsive-navbar-nav">
                    <Nav className="ms-auto">
                        <SigninModal/>
                        <Navbar.Text className="btn btn-link" as="span">
                            Sign up
                        </Navbar.Text>
                    </Nav>
                </Navbar.Collapse>
            </Container>
      </Navbar>
    );
}


export default NavigationBar;