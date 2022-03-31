import { format } from "date-fns";
import React from "react";
import { Col, Nav, Row } from "react-bootstrap";
import PropTypes from 'prop-types';



function ConversationTab ( { user, conversation }) {
    const otherParticipant = conversation.participants.find(participant => participant.user.id !== user.id);
    let dateLastMessage;
    if (conversation.lastMessage) {
        dateLastMessage = new Date(conversation.lastMessage.date).getTime();        
    }


    return (
        <Nav.Item>
            <Nav.Link className="p-3 m-0 border-bottom" style={{ cursor: 'pointer', display: 'flex' }} as={Row} eventKey={conversation.id}>
                <Col className="p-0 text-start" xs={9}>
                    <div className="font-weight-bold h6 m-0 text-truncate text-dark">{otherParticipant.user.name}</div>
                    {conversation.lastMessage && <div title={conversation.lastMessage.content} className="font-italic text-truncate text-secondary">{conversation.lastMessage.content}</div>}
                </Col>
                {conversation.lastMessage &&
                    <Col className="pt-1 small" xs={3}>
                        <div>{format(dateLastMessage, 'HH:mm a')}</div>
                        <div>{format(dateLastMessage, 'dd/MM/yyyy')}</div>
                    </Col>
                }
            </Nav.Link>
        </Nav.Item>
    );
}

ConversationTab.propTypes = {
    user: PropTypes.shape({
        id: PropTypes.string.isRequired,
        createdAt: PropTypes.string.isRequired,
        email: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired
    }),
    index: PropTypes.number,
    conversation: PropTypes.shape({
        id: PropTypes.string.isRequired,
        participants: PropTypes.arrayOf(PropTypes.shape({
            user: PropTypes.shape({
                id: PropTypes.string.isRequired,
                name: PropTypes.string.isRequired
            }).isRequired
        })).isRequired,
        lastMessage: PropTypes.shape({
            id: PropTypes.string.isRequired,
            date: PropTypes.string.isRequired,
            content: PropTypes.string.isRequired,
            sender: PropTypes.shape({
                user: PropTypes.shape({
                    id: PropTypes.string.isRequired
                }).isRequired
            }).isRequired
        })
    })
};

export default ConversationTab;
