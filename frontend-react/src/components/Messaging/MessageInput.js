import { faPaperPlane } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useEffect, useRef } from "react";
import { Button, Col, Form, Row } from "react-bootstrap";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import PropTypes from 'prop-types';
import { createMessage } from "../../requests/messaging";


function MessageInput ({ fullConversation, setFullConversation, conversations, setConversations }) {
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const inputRef = useRef();


    useEffect(() => inputRef.current && inputRef.current.focus());

    const handleSubmit = async (e) => {
        e.preventDefault();

        const content = inputRef.current.value;

        if (content === '') return;

        inputRef.current.value = '';

        console.log(fullConversation.id);

        const message = await createMessage(fullConversation.id, content, auth, updateAuth);

        const newConversation = fullConversation;
        newConversation.messages = [...fullConversation.messages, message];

        setFullConversation(newConversation);

        const newConversations = conversations.filter(conversation => conversation.id !== newConversation.id);
        newConversation.lastMessage = message;

        setConversations([newConversation, ...newConversations]);
    }

    return (
        <div style={{ bottom: '0', zIndex: '5' }} className="mt-2 pt-2 pb-2 bg-white position-sticky">
            <Form onSubmit={(e) => handleSubmit(e)}>
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
    fullConversation: PropTypes.shape({
        id: PropTypes.string.isRequired,
        participants: PropTypes.arrayOf(PropTypes.shape({
            user: PropTypes.shape({
                id: PropTypes.string.isRequired,
                name: PropTypes.string.isRequired
            }).isRequired
        })).isRequired,
        messages: PropTypes.arrayOf(PropTypes.shape({
            id: PropTypes.string.isRequired,
            date: PropTypes.string.isRequired,
            content: PropTypes.string.isRequired,
            sender: PropTypes.shape({
                user: PropTypes.shape({
                    id: PropTypes.string.isRequired
                }).isRequired
            }).isRequired
        }))
    })
};

export default MessageInput;
