import React, { useEffect, useRef, useState } from 'react';
import { Button, Modal, Form, Spinner, Alert } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';

import PropTypes from 'prop-types';
import { useAuth, useAuthUpdate } from '../../contexts/AuthContext';
import { getUser } from '../../requests/user';

function CreateConversationModal ({ user, setShowMessages, conversations, setConversations, setActiveKey, setTempConversation }) {
    const [modal, setModal] = useState(false);
    const [loading, setLoading] = useState(false);
    const [inError, setInError] = useState(false);
    const updateAuth = useAuthUpdate();
    const auth = useAuth();
    const [userFound, setUserFound] = useState(null);

    const inputRef = useRef();
    useEffect(() => inputRef.current && inputRef.current.focus(), [modal]);

    const toggleModal = () => setModal(!modal);

    const handleCancel = () => {
        setInError(false);
        toggleModal();
    };

    const handleFind = async (e) => {
        e.preventDefault();
        const userId = inputRef.current.value;

        if (userId === '') return;

        inputRef.current.value = '';
        setLoading(true);
        // const conversation = await createConversation(userId, auth, updateAuth);
        const newUser = await getUser(userId, auth, updateAuth);
        setLoading(false);

        if (newUser === null || user.id === userId) {
            setInError(true);
            setUserFound(null);

            return;
        }


        

        // toggleModal();
        // setConversations([conversation, ...conversations]);
        setUserFound(newUser);
        setInError(false);
    };

    const handleChat = async (e) => {
        e.preventDefault();
        const newConversation = {
            id: (new Date().getTime()).toString(),
            participants: [
                {
                    user: userFound
                }
            ]
        }
        

        if (userFound) {
            let isExist = false;
            let conversationId;
            conversations.forEach((value) => {
                if (value.lastMessage)
                    if (value.participants[0].user.id === userFound.id || value.participants[1].user.id === userFound.id) {
                        isExist = true;
                        conversationId = value.id;
                    }
            })

            if (isExist) {
                setActiveKey(conversationId);
                toggleModal();
            } else {
                setConversations([newConversation, ...conversations]);
                setActiveKey(newConversation.id);
                setTempConversation(newConversation);
                toggleModal();
            }

        }

        

        
    }

    return (
        <div>
            <Button className="mb-2" onClick={toggleModal} variant="success">
                <FontAwesomeIcon className="me-2" icon={faPlus}/>New Conversation
            </Button>
            <Modal size="md" show={modal} onHide={handleCancel}>
                <Modal.Header closeButton>
                    <Modal.Title>
                        New Conversation
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Form>
                        <Form.Group className="mt-1 mb-4">
                            <Form.Label>User<span className="ms-1 text-danger">*</span></Form.Label>
                            <Form.Control
                                ref={inputRef} id="id" name="id" type="text" placeholder="daf93d16-8884-4948-955..."
                            />
                        </Form.Group>
                        {userFound != null &&
                        <div className='d-flex justify-content-between align-items-center'>
                            <div>
                                User Found! <span className="text-warning">{userFound.name}</span>
                            
                        </div>
                            <Button variant="warning" className="text-white" onClick={handleChat}>Chat Now</Button>
                        </div>
                       
                        
                        
                        }
                        {inError &&
                        <Alert variant="danger" onClose={() => setInError(false)} dismissible>
                            User Not Found
                        </Alert>
                        }
                        <div className="d-flex justify-content-around mt-4">
                            {loading
                                ? <Spinner animation="border" variant="primary"/>
                                : <Button className="me-4 ms-4" variant="primary" type="submit" onClick={handleFind}>Find User</Button>
                            }
                        </div>
                    </Form>
                </Modal.Body>
            </Modal>
        </div>
    );
}

CreateConversationModal.propTypes = {
    setActiveKey: PropTypes.func,
    conversations: PropTypes.array,
    setConversations: PropTypes.func
};

export default CreateConversationModal;
