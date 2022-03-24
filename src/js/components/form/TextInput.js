import React from "react";
import { useField } from "formik";
import { Form } from "react-bootstrap";

const TextInput = ({ label, helpText, mb, ...props }) => {

  const [field, meta] = useField(props);
  const marginBotton = mb || "mb-3";

  return (
    <>
      <Form.Group className={marginBotton} controlId={props.id || props.name}>
        <Form.Label>{label}</Form.Label>
        <Form.Control
          isValid={meta.touched && !meta.error}
          isInvalid={meta.touched && meta.error}
          {...field}
          {...props}
        />

        {meta.touched && meta.error ? (
          <Form.Control.Feedback type="invalid">
            {meta.error}
          </Form.Control.Feedback>
        ) : null}

        {helpText &&
          <Form.Text className="text-muted">
            {helpText}
          </Form.Text>
        }
      </Form.Group>
    </>
  );
};

export default TextInput;