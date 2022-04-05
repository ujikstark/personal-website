import React, { useEffect, useRef, useState } from "react";
import { useAuth, useAuthUpdate } from "../../contexts/AuthContext";
import PropTypes from 'prop-types';
import { createMercureEventSource, getConversation } from "../../requests/messaging";
import MessageBubble from "./MessageBubble";
import ConversationHeader from "./ConversationHeader";
import MessageInput from "./MessageInput";

function ConversationDisplay ({ user, conversation, setShowMessages, conversations, setConversations }) {
    const [loading, setLoading] = useState(true);
    const [fullConversation, setFullConversation] = useState({});
    const [eventSource, setEventSource] = useState(null);
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    const divRef = useRef();

    useEffect(() => {
        divRef.current?.scrollIntoView({ behavior: 'smooth' });
    });

    useEffect(() => {
        const newEventSource = eventSource ?? createMercureEventSource('http://localhost:8000/api/conversations/{id}');
        newEventSource.onmessage = function (event) {
          
            const message = JSON.parse(event.data);

            if (message.conversation.id === fullConversation.id) {
                fullConversation.messages = [...fullConversation.messages, message];

                setFullConversation(fullConversation);
            }

            const newConversations = conversations.map(conversation =>
                conversation.id === message.conversation.id
                    ? { ...conversation, lastMessage: message }
                    : conversation);

            newConversations.sort(function (c1, c2) {
                if (c1.lastMessage === null) return 1;
                if (c2.lastMessage === null) return -1;

                return c2.lastMessage.date - c1.lastMessage.date;
            });

            setConversations(newConversations);
        };

        setEventSource(newEventSource);
    }, [conversation, conversations]);
    
    useEffect(() => {
        (async () => {
            const fetchedConversation = await getConversation(conversation.id, auth, updateAuth);
            setFullConversation(fetchedConversation);
            setLoading(false);
        })();

        return () => setLoading(false);
    }, [auth, updateAuth]);


    const renderMessageBubble = (message, index, array) => {
        const previousMessage = index === 0 ? null : array[index - 1];
        return <MessageBubble key={index} user={user} previousMessage={previousMessage} message={message}/>;
    };

    return (
        <>
            <ConversationHeader setShowMessages={setShowMessages} conversation={conversation} user={user}/>
            {!loading &&
                <>
                    <div className="pr-3 pl-3 d-flex flex-column">
                    {fullConversation.messages.map((message, index, array) => (
                        renderMessageBubble(message, index, array)
                        ))}
                    </div>
                    <MessageInput conversations={conversations} setConversations={setConversations} fullConversation={fullConversation} setFullConversation={setFullConversation}/>
                </>
            }
            <div ref={divRef}/>
            
        </>
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