import { useEffect, useRef, useState } from "react";
import { Form, Modal, Button, Alert, Spinner } from "react-bootstrap";
import useUserFormValidation from "../hooks/useUserFormValidation";
import { getMe, signinSubmit } from "../requests/user";
import UserFormInput from "./UserFormInput";
import { useAuthUpdate } from "../contexts/AuthContext";
import ResetPasswordEmailModal from "./ResetPasswordEmailModal";

function SigninModal () {

    const [modal, setModal] = useState(false);
    const [inError, setInError] = useState(false);
    const [loading, setLoading] = useState(false);
    const { values, handleChange, clearAll } = useUserFormValidation(); 
    const updateAuth = useAuthUpdate();

    const innerRef = useRef();
    
    useEffect(() => {
        innerRef.current && innerRef.current.focus()
    }, [modal]);
    
    const inputTypes = ['email', 'password'];
    const isFormFilled = values.password && values.email;
    
    const handleCancel = () => {
        setInError(false);
        clearAll();
    }

    const handleSigninSubmit = async () => {
        setLoading(true);
        const { auth, user } = await signinSubmit(values);
        setLoading(false);

        if (!auth.isAuthenticated) {
            setInError(true);

            return;
        }
        
        updateAuth(auth);
    }


    return (
        <>
            <Button onClick={()=> setModal(true)} variant="primary">Sign in</Button>
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
                    </Form>
                    {inError &&
                        <Alert className="mt-4" variant="danger" onClose={() => setInError(false)} dismissible>
                            <p>Incorrect username or password.</p>
                        </Alert>
                    }
                    <div className="d-flex justify-content-around mt-4">
                        {loading
                            ? <Spinner animation="border" variant="primary"/>
                            : <Button disabled={!isFormFilled} className="mr-4 ml-4" variant="primary" type="submit" onClick={handleSigninSubmit}>Sign in</Button>
                        }
                    </div>             
                    <div className="d-flex justify-content-around mt-2">
                        <ResetPasswordEmailModal/>
                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SigninModal;