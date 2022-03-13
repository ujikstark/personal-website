import { useEffect, useRef, useState } from "react";
import { Form, Modal, Button, Alert } from "react-bootstrap";
import useUserFormValidation from "../hooks/useUserFormValidation";
import { getMe, signinSubmit } from "../requests/user";
import UserFormInput from "./UserFormInput";
import { useAuthUpdate } from "../contexts/AuthContext";

function SigninModal () {

    const [modal, setModal] = useState(false);
    const { values, handleChange, clearAll } = useUserFormValidation(); 
    const [inError, setInError] = useState(false);
    const updateAuth = useAuthUpdate();

    const innerRef = useRef();
    
    useEffect(() => {
        innerRef.current && innerRef.current.focus()
    }, [modal]);
    
    const inputTypes = ['email', 'password'];
    const isFormFilled = values.password && values.email;
    
    const toggleModal = () => setModal(!modal);

    const handleCancel = () => {
        setInError(false);
        clearAll();
        toggleModal();
    }

    const handleSigninSubmit = async () => {
        
        const { auth, user } = await signinSubmit(values);

        if (!auth.isAuthenticated) {
            setInError(true);

            return;
        }

        updateAuth(auth);

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
                            <Alert className="mt-4" variant="danger" onClose={() => setInError(false)} dismissible>
                                <p>Incorrect username or password.</p>
                            </Alert>
                        }
                        <div className="d-grid mt-4">
                            <Button disabled={!isFormFilled} className="mr-4 ml-4" variant="primary" type="submit" onClick={handleSigninSubmit} href="#">Sign in</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SigninModal;