import { useEffect, useRef, useState } from "react";
import { Form, Modal, Button, Alert } from "react-bootstrap";
import useUserFormValidation from "../hooks/useUserFormValidation";
import UserFormInput from "./UserFormInput";

function SigninModal () {

    const [modal, setModal] = useState(false);
    const { values, handleChange, clearAll } = useUserFormValidation(); 
    const [inError, setInError] = useState(false);
    
    const innerRef = useRef();
    
    useEffect(() => {
        setTimeout(() => {
            innerRef.current && innerRef.current.focus()
        }, 4000) 
    }, [modal]);
    
    const inputTypes = ['email', 'password'];
    const isFormFilled = values.password && values.email;
    
    const toggleModal = () => setModal(!modal);

    const handleCancel = () => {
        setInError(false);
        clearAll();
        toggleModal();
    }

    const handleSigninSubmit = () => {
        setInError(true);

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
                        {inputTypes.map((type, index) => (
                                <UserFormInput
                                    type={type}
                                    asterisk={false}
                                    innerRef={innerRef}
                                    values={values}
                                    errors={{}}
                                    touched={{}}
                                    handleChange={handleChange}
                                    key={index}
                                />
                        ))}
                        {inError &&
                            <Alert variant="danger" onClose={() => setInError(false)} dismissible>
                                <p>Incorrect username or password.</p>
                            </Alert>
                        }
                        <div className="d-flex justify-content-around mt-4">
                            <Button className="mr-4 ml-4" variant="primary" type="submit" onClick={handleSigninSubmit} href="#">Sign in</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SigninModal;