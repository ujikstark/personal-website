import { useState } from "react";
import { Form, Modal, Button } from "react-bootstrap";
import { useAuthUpdate } from "../contexts/AuthContext";

function SigninModal () {

    const [modal, setModal] = useState(false);
    const updateAuth = useAuthUpdate();
    const toggleModal = () => setModal(!modal);

    const handleCancel = () => {
        toggleModal();
    }

    const handleSigninSubmit = () => {
        updateAuth(true);
    }

    return (
        <>
            <Button onClick={toggleModal} variant="primary">Sign in</Button>
            <Modal size="md" show={modal} onHide={handleCancel}>
                <Modal.Header closeButton>
                    <Modal.Title>Sign in</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form>
                        <Form.Group className="mb-3" controlId="formEmail">
                            <Form.Label>Email</Form.Label>
                            <Form.Control type="email" placeholder="Your email address" />
                        </Form.Group>

                        <Form.Group className="mb-3" controlId="formPassword">
                            <Form.Label>Password</Form.Label>
                            <Form.Control type="password" placeholder="Password" />
                        </Form.Group>    
                        <div className="d-flex justify-content-around mt-4">
                            <Button className="mr-4 ml-4" variant="primary" type="submit" onClick={handleSigninSubmit}>Sign in</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SigninModal;