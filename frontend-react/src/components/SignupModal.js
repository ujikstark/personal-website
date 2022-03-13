import { useEffect, useRef, useState } from "react";
import { Form, Modal, Button, Navbar, Alert } from "react-bootstrap";
import { useAuthUpdate } from "../contexts/AuthContext";
import useUserFormValidation from "../hooks/useUserFormValidation";
import UserFormInput from "./UserFormInput";

function SignupModal () {

    const [modal, setModal] = useState(false);
    const { values, errors, touched, handleChange, clearAll } = useUserFormValidation();
    const [inError, setInError] = useState(false);

    const innerRef = useRef();

    const inputTypes = ['email', 'name', 'password', 'confirmPassword'];
    const isFormValid = Object.keys(errors).length === 0 && Object.keys(touched).length === inputTypes.length;


    useEffect(() => {
        innerRef.current && innerRef.current.focus()       
    }, [modal]);

    const toggleModal = () => {
        setModal(!modal);
    }

    const handleCancel = () => {
        clearAll();
        toggleModal();  
    }

    const handleSignupSubmit = () => {
        
        setInError(true);
    
    }


    return (
        <>
            <Navbar.Text onClick={toggleModal} className="btn btn-link" as="span">Sign up</Navbar.Text>
            <Modal size="md" show={modal} onHide={handleCancel}>
                <Modal.Header closeButton>
                    <Modal.Title>Sign up</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form>
                        {inputTypes.map((type, index) => (
                            <UserFormInput
                                type={type}
                                asterisk={true}
                                innerRef={innerRef}
                                values={values}
                                errors={errors}
                                touched={touched}
                                handleChange={handleChange}
                                key={index}
                            />
                        ))}
                        {inError &&
                        <Alert className="mt-4" variant="danger" onClose={() => setInError(false)} dismissible>
                            <p>This address email is already taken, try another one.</p>
                        </Alert>
                        }
                        <div className="d-grid mt-4">   
                            <Button type="hidden" disabled={!isFormValid} className="mr-4 ml-4" variant="primary" size="lg" onClick={handleSignupSubmit} href="#">Sign up</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SignupModal;