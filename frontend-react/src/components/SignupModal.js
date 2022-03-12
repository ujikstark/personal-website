import { useEffect, useRef, useState } from "react";
import { Form, Modal, Button, Navbar } from "react-bootstrap";
import { useAuthUpdate } from "../contexts/AuthContext";
import useUserFormValidation from "../hooks/useUserFormValidation";
import UserFormInput from "./UserFormInput";

function SignupModal () {

    const [modal, setModal] = useState(false);
    const { values, errors, touched, handleChange, clearAll } = useUserFormValidation();

    const innerRef = useRef();

    useEffect(() => innerRef.current && innerRef.current.focus(), [modal]);

    const toggleModal = () => {
        setModal(!modal);
    }

    const handleCancel = () => {
        toggleModal();  
    }

    const inputTypes = ['email', 'name', 'password', 'confirmPassword'];


    return (
        <>
            <Navbar.Text onClick={toggleModal} className="btn btn-link">Sign up</Navbar.Text>
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
                  
                        <div className="d-grid mt-4">
                            <Button className="mr-4 ml-4" variant="primary" type="submit" size="lg">Sign up</Button>
                        </div>             

                    </Form>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default SignupModal;