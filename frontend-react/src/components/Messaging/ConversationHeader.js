import React, { useState } from "react";
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import { Button } from "react-bootstrap";


function ConversationHeader ({ user, conversation, setShowMessages }) {
    const otherParticipant = conversation.participants.find(participant => participant.user.id !== user.id);
    const [isMobile] = useState(Number(window.innerWidth) < 768);

    return (
        <div style={{ top: '0', backgroundColor: 'gainsboro', zIndex: '5' }} className="p-3 text-start position-sticky">
            {isMobile && <Button variant="light" onClick={() => setShowMessages(false)}>
                <FontAwesomeIcon icon={faArrowLeft}/>
            </Button>}

            <span className="font-weight-bold ms-3">{otherParticipant.user.name}</span>
        </div>
    );

}


ConversationHeader.propTypes = {
    setShowMessages: PropTypes.func,
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

export default ConversationHeader;