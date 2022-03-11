import { useState } from "react";
import { Form, Modal, Button, Navbar } from "react-bootstrap";
import { useAuthUpdate } from "../contexts/AuthContext";

function SignupModal () {

    const [modal, setModal] = useState(false);
    const toggleModal = () => setModal(!modal);

    const handleCancel = () => {
        toggleModal();
    }


    return (
        <>
            <Navbar.Text onClick={toggleModal} className="btn btn-link">Sign up</Navbar.Text>
            <Modal size="md" show={modal} onHide={handleCancel}>
                <Modal.Header closeButton>
                    <Modal.Title>Sign up</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form>
                        <Form.Group className="mb-3" controlId="formName">
                            <Form.Label>Full Name</Form.Label>
                            <Form.Control type="text" placeholder="Your Name" />
                        </Form.Group>
                        <Form.Group className="mb-3" controlId="formEmail">
                            <Form.Label>Email</Form.Label>
                            <Form.Control type="email" placeholder="Your email address" />
                        </Form.Group>

                        <Form.Group className="mb-3" controlId="formPassword">
                            <Form.Label>Password</Form.Label>
                            <Form.Control type="password" placeholder="Password" />
                        </Form.Group>    
                        <div className="d-flex justify-content-around mt-4">
                            <Button className="mr-4 ml-4" variant="primary" type="submit" block>Sign up</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SignupModal;