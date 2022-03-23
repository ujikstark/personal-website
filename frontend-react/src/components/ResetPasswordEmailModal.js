import { useEffect, useRef, useState } from 'react';
import { Button, Modal, Form, Spinner, Alert } from 'react-bootstrap';
import useUserFormValidation from '../hooks/useUserFormValidation';
import UserFormInput from './UserFormInput';

function ResetPasswordEmailModal () {
    const [modal, setModal] = useState(false);
    const { values, errors, touched, handleChange, clearAll } = useUserFormValidation();
    const [loading, setLoading] = useState(false);
    const [inError, setInError] = useState(false);
    const isFormValid = values.email !== '' && Object.keys(errors).length === 0;

    const innerRef = useRef();
    useEffect(() => innerRef.current && innerRef.current.focus(), [modal]);

    const toggleModal = () => {
        setModal(!modal);
    }

    const handleCancel = () => {
        toggleModal();
        clearAll();
    }

    return (
        <>
            <Button onClick={toggleModal} variant="link">Forgotten Password</Button>
            <Modal size="md" show={modal} onHide={handleCancel}>
                <Modal.Header closeButton>
                    <Modal.Title>Reset your password</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className='text-justify mb-3'>
                    You will receive a link for allowing you to reset your password.
                    </div>
                    <Form>
                        <UserFormInput
                            type="email"
                            asterisk={false}
                            innerRef={innerRef}
                            values={values}
                            errors={errors}
                            touched={touched}
                            handleChange={handleChange}
                        />
                    </Form>
                    {inError &&
                    <Alert variant="danger" onClose={() => setInError(false)} dismissible>
                        <p>An error was encountered when sending the email.</p>
                    </Alert>
                    }
                    <div className="d-flex justify-content-around mt-4 mb-2">
                        {loading
                            ? <Spinner animation="border" variant="primary"/>
                            : <Button className="" disabled={!isFormValid} variant="primary" block>
                                Send
                            </Button>
                        }
                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
}

export default ResetPasswordEmailModal;