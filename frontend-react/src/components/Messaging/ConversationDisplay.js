import React, { useEffect, useRef, useState } from "react";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import PropTypes from 'prop-types';
import { createMercureEventSource, getConversation } from "../../requests/messaging";
import MessageBubble from "./MessageBubble";
import ConversationHeader from "./ConversationHeader";
import MessageInput from "./MessageInput";
import { Spinner } from "react-bootstrap";

function ConversationDisplay ({ user, conversation, setShowMessages, conversations, setConversations, tempConversation, setTempConversation, newMessages, setNewMessages }) {
    const [loading, setLoading] = useState(true);
    const [fullConversation, setFullConversation] = useState(conversation);
    const auth = useAuth();
    const updateAuth = useAuthUpdate();


    const divRef = useRef();

    useEffect(() => {
        divRef.current?.scrollIntoView({ behavior: 'smooth' });
    });
    
    useEffect(() => {
        
        if (!loading) {
            if (typeof newMessages !== "undefined") {
                if (newMessages.hasOwnProperty(fullConversation.id)) {
                    if (newMessages[fullConversation.id].length) {
                        let newFullConversation = fullConversation;
                        newFullConversation.messages = newFullConversation.messages.concat(newMessages[fullConversation.id]);
                        
                        let newM = newMessages;
                        newM[fullConversation.id] = [];
                        
                        setFullConversation(newFullConversation);
                        setNewMessages(newM);
                    } 
                }
            }

        }


    }, [newMessages]);

    
    useEffect(() => {

        (async () => {

            if (conversation.id != tempConversation.id) {
                
                const fetchedConversation = await getConversation(conversation.id, auth, updateAuth);

                setFullConversation(fetchedConversation);
                setLoading(false);
            }   
            
        })();
        
        return () => setLoading(false);
    }, [auth, updateAuth]);


    const renderMessageBubble = (message, index, array) => {
        const previousMessage = index === 0 ? null : array[index - 1];
        return <MessageBubble key={index} user={user} previousMessage={previousMessage} message={message}/>;
    };

    useEffect(() => {
        
        if (fullConversation.messages) setLoading(false);
    }, [fullConversation.messages]);
    

    return (
        <div className="h-100">
            <ConversationHeader setShowMessages={setShowMessages} conversation={conversation} user={user}/>
            {fullConversation != null  && !loading ?
    
                <>
                    <div className="h-100 d-flex flex-column">
                    {fullConversation.messages.map((message, index, array) => (
                        renderMessageBubble(message, index, array)
                        ))}
                    </div>
                    <MessageInput setTempConversation={setTempConversation} tempConversation={tempConversation} conversations={conversations} setConversations={setConversations} fullConversation={fullConversation} setFullConversation={setFullConversation}/>
                </>
                : 
                <>
                    <MessageInput setTempConversation={setTempConversation}tempConversation={tempConversation} conversations={conversations} setConversations={setConversations} fullConversation={fullConversation} setFullConversation={setFullConversation}/>
                </>
            }
            
            <div ref={divRef}/>
            
        </div>
    );


}

ConversationDisplay.propTypes = {
    setShowMessages: PropTypes.func,
    conversations: PropTypes.array,
    setConversations: PropTypes.func,
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

export default ConversationDisplay;