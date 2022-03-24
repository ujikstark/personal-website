import { Alert, Button, Card, Container, Form, Spinner } from "react-bootstrap";
import UserFormInput from "../components/UserFormInput";
import useUserFormValidation from "../hooks/useUserFormValidation";
import { useAuth, useAuthUpdate } from '../contexts/AuthContext';
import { useNavigate, useParams } from 'react-router-dom';
import { resetPassword } from "../requests/resetPassword";
import { signinSubmit } from "../requests/user";
import { useState } from "react";

function ResetPassword () {

    let navigate = useNavigate();
    const params = useParams();
    const { values, errors, touched, handleChange } = useUserFormValidation();
    const [loading, setLoading] = useState(false);
    const [inError, setInError] = useState(false);
    const inputTypes = ['password', 'confirmPassword'];
    const auth = useAuth();
    const updateAuth = useAuthUpdate();

    if (auth !== null) {
        navigate("/", { replace: true });
    }

    const handleSubmit = async () => {
        setLoading(true);
        setInError(false);

        const response = await resetPassword(params.token, values);
        setLoading(false);

        if (response === null) {
            setInError(true);

            return;
        }

        const { auth } = await signinSubmit({ email: response.email, password: values.password})

        updateAuth(auth);
    }

    return (
        <Container className="d-flex justify-content-around">
            <Card style={{ width: '40rem' }} className="m-2 p-3 shadow">
                <Card.Body>
                    <Card.Title>
                        Reset your password
                    </Card.Title>
                    <Form className="text-start">
                        {inputTypes.map((type, index) => (
                            <UserFormInput
                                type={type}
                                asterisk={true}
                                innerRef={{}}
                                values={values}
                                errors={errors}
                                touched={touched}
                                handleChange={handleChange}
                                key={index}
                            />
                        ))}
                        {inError &&
                        <Alert variant="danger" onClose={() => setInError(false)} dismissible>
                            The link is invalid or expired.
                        </Alert>
                        }
                        <div className="d-flex justify-content-around mt-4">
                            {loading
                                ? <Spinner animation="border" variant="primary"></Spinner>
                                : <Button className="mr-4 ml-4" disabled={false} variant="primary" onClick={handleSubmit} block>
                                    Reset
                                </Button>
                            }
                        </div>
                    </Form>
                </Card.Body>
            </Card>
        </Container>
    );
}

export default ResetPassword;