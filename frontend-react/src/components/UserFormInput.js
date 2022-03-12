import { Form } from "react-bootstrap";
import userFormText from "../helper/userFormText";


function UserFormInput ({ type, asterisk, innerRef, handleChange, values, errors, touched}) {
    const ref = type === 'email' ? innerRef : null;
    const htmlType = type.toLowerCase().includes('password') ? 'password' : type;

    console.log(errors[type]);

    return (
        <Form.Group>
            <Form.Label htmlFor={type}>{userFormText[type].label}{asterisk && <span className="text-danger"> *</span>}</Form.Label>
            {type === 'currentPassword'
                ? <Form.Control
                    ref={ref} onChange={handleChange}
                    values={values['type']} type={htmlType} name={type} id={type} 
                />
                : <Form.Control
                    ref={ref} 
                    onChange={handleChange}
                    isInvalid={touched[type] && errors[type]} isValid={touched[type] && !errors[type]}
                    values={values['type']} type={htmlType} name={type} id={type}
                />  
            }
            <Form.Control.Feedback type="invalid">{errors[type]}</Form.Control.Feedback>
        </Form.Group>
    );
}   

export default UserFormInput;