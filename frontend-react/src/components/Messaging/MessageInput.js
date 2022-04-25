import { faPaperPlane } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useEffect, useRef } from "react";
import { Button, Col, Form, Row } from "react-bootstrap";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import PropTypes from 'prop-types';
import { createConversation, createMessage } from "../../requests/messaging";


function MessageInput ({ tempConversation, setTempConversation, fullConversation, setFullConversation, conversations, setConversations }) {
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const inputRef = useRef();


    useEffect(() => inputRef.current && inputRef.current.focus());

    const handleSubmit = async (e) => {
        e.preventDefault();

        const content = inputRef.current.value;

        if (content === '') return;

        inputRef.current.value = '';

        let newConversation, message;

        if (tempConversation.length !== 0) {
            newConversation = await createConversation(tempConversation.participants[0].user.id, auth, updateAuth);
            if (message = await createMessage(newConversation.id, content, auth, updateAuth)) {

                newConversation.messages.push(message);
            }
        } else {
            message = await createMessage(fullConversation.id, content, auth, updateAuth);
            newConversation = fullConversation;
            newConversation.messages = [...fullConversation.messages, message];
        }

        setFullConversation(newConversation);

        const newConversations = conversations.filter(conversation => conversation.id !== newConversation.id);
        newConversation.lastMessage = message;

        setConversations([newConversation, ...newConversations]);
        setTempConversation([]);
    }

    return (
        <div style={{ bottom: '0', zIndex: '5' }} className="mt-2 pt-2 pb-2 bg-white position-sticky">
            <Form onSubmit={ (e) => handleSubmit(e)}>
                <Row className="m-0">
                    <Col className="m-0" xs={10}>
                        <Form.Group className="m-0">
                            <Form.Control
                                autoComplete="off"
                                style={{ borderRadius: '2rem' }}
                                ref={inputRef} id="message" name="message" type="text" placeholder="Message..."
                            />
                        </Form.Group>
                    </Col>
                    <Col className="text-left m-0 p-0" xs={2}>
                        <Button className="rounded-circle" style={{ marginTop: '0.125rem' }} type="submit">
                            <FontAwesomeIcon icon={faPaperPlane}/>
                        </Button>
                    </Col>
                </Row>
            </Form>
        </div>
    );
}

MessageInput.propTypes = {
    setConversations: PropTypes.func,
    setFullConversation: PropTypes.func,
    conversations: PropTypes.array,
};

export default MessageInput;
