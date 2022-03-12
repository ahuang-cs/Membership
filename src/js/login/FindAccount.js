
import React, { useEffect, useState } from "react";
import * as tenantFunctions from "../classes/Tenant";
import { Formik } from "formik";
import * as yup from "yup";
import { Form } from "react-bootstrap";
import { Button } from "react-bootstrap";
import { connect } from "react-redux";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";
import axios from "axios";

const schema = yup.object().shape({
  emailAddress: yup.string().email("Your email address must be valid").required("You must provide an email address"),
});

const FindAccount = (props) => {

  useEffect(() => {
    tenantFunctions.setTitle("Get back into your account");
    props.setType("resetPassword");
  }, []);

  const [error, setError] = useState(null);

  const onSubmit = async (values, { setSubmitting }) => {
    setSubmitting(true);

    try {

      const response = await axios.post("/api/auth/request-password-reset", {
        email_address: values.emailAddress,
      });

      if (response.data.success) {
        // props.setType("twoFactor");
        // props.setLoginDetails(response.data);
      } else {
        // There was an error
        setError({
          type: "danger",
          message: response.data.message,
        });
      }

    } catch (error) {
      setError({
        type: "danger",
        message: error.message,
      });
    }

    setSubmitting(false);
  };

  return (

    <>

      {
        error &&
        <div className="alert alert-danger">{error.message}</div>
      }

      <Formik
        validationSchema={schema}
        onSubmit={onSubmit}
        initialValues={{
          emailAddress: props.emailAddress || "",
          password: "",
          rememberMe: props.rememberMe || true,
        }}
      >
        {({
          handleSubmit,
          handleChange,
          handleBlur,
          values,
          touched,
          isValid,
          errors,
          isSubmitting,
        }) => (
          <Form noValidate onSubmit={handleSubmit} onBlur={handleBlur}>
            <div className="mb-3">
              <Form.Group controlId="emailAddress">
                <Form.Label>Email address</Form.Label>
                <Form.Control
                  type="email"
                  name="emailAddress"
                  value={values.emailAddress}
                  onChange={handleChange}
                  isValid={touched.emailAddress && !errors.emailAddress}
                  isInvalid={touched.emailAddress && errors.emailAddress}
                  size="lg"
                />
                {errors.emailAddress &&
                  <Form.Control.Feedback type="invalid">{errors.emailAddress}</Form.Control.Feedback>
                }
              </Form.Group>
            </div>

            <p className="mb-5">
              <Button size="lg" type="submit" disabled={!isValid || isSubmitting}>Reset password</Button>
            </p>
          </Form>
        )}
      </Formik>

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(FindAccount);