import React from "react";
import PropTypes from 'prop-types';
import { format } from "date-fns";


function MessageBubble ({ user, message, previousMessage }) {
    const isLoggedInUserMessage = message.sender.user.id === user.id;
    const isPreviousMessageSameSender = previousMessage !== null && previousMessage.sender.user.id === message.sender.user.id;

    const loggedInUserMessageClasses = 'bg-primary align-self-end';
    const otherUserMessageClasses = 'bg-success align-self-start';
    let messageDate;
    if (message.date) {
        messageDate = new Date(message.date).getTime();        
    }


    return (
        <>
            <div style={{ borderRadius: '12px', maxWidth: '75%' }}
                className={`text-start text-white p-1 me-2 ms-2 pe-2 ps-2 ${isPreviousMessageSameSender ? 'mt-1' : 'mt-2'} ${isLoggedInUserMessage ? loggedInUserMessageClasses : otherUserMessageClasses}`}
            >
                <div><span className="text-break">{message.content}</span><span style={{ width: '75px' }} className="d-inline-block"/></div>
                <div className="float-right text-end position-relative" style={{ fontSize: '65%', top: '-15px', marginBottom: '-12px', lineHeight: '15px' }}>
                    <div style={{ height: '11px' }}>{format(messageDate, 'HH:mm a')}</div>
                    <div style={{ height: '11px' }}>{format(messageDate, 'dd/MM/yyyy')}</div>
                </div>
            </div>
        </>
    )

}

MessageBubble.propTypes = {
    user: PropTypes.shape({
        id: PropTypes.string.isRequired,
        createdAt: PropTypes.string.isRequired,
        email: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired
    }),
    message: PropTypes.shape({
        id: PropTypes.string.isRequired,
        content: PropTypes.string.isRequired,
        date: PropTypes.string.isRequired,
        sender: PropTypes.shape({
            user: PropTypes.shape({
                id: PropTypes.string.isRequired,
                name: PropTypes.string
            })
        })
    }),
    previousMessage: PropTypes.shape({
        id: PropTypes.string.isRequired,
        content: PropTypes.string.isRequired,
        date: PropTypes.string.isRequired,
        sender: PropTypes.shape({
            user: PropTypes.shape({
                id: PropTypes.string.isRequired,
                name: PropTypes.string
            })
        })
    })
};

export default MessageBubble;