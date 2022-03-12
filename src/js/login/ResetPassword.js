
import React, { useEffect, useState } from "react";
import * as tenantFunctions from "../classes/Tenant";
import { Formik } from "formik";
import * as yup from "yup";
import { Alert, Form } from "react-bootstrap";
import { Button } from "react-bootstrap";
import { connect } from "react-redux";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";
import axios from "axios";
import { isPwned } from "../classes/Passwords";
import { useSearchParams } from "react-router-dom";

const schema = yup.object().shape({
  password: yup.string().min(8, "Your password must be at least 8 characters").required("You must provide a password").test(
    "is-pwned",
    "Your password is insecure",
    async (value) => await isPwned(value),
  ),
});

const ResetPassword = (props) => {

  useEffect(async () => {
    tenantFunctions.setTitle("Get back into your account");
    props.setType("resetPassword");

    // Check token
    const response = await axios.post("/api/auth/can-password-reset", {
      token: searchParams.get("auth-code"),
    });
    setIsValid(response.data.success);

  }, []);

  const [error, setError] = useState(null);
  const [isValid, setIsValid] = useState(false);
  const [searchParams] = useSearchParams();

  const onSubmit = async (values, { setSubmitting }) => {
    setSubmitting(true);

    try {

      const response = await axios.post("/api/auth/login/login", {
        email_address: values.emailAddress,
        password: values.password,
      });

      if (response.data.success) {
        props.setType("twoFactor");
        props.setLoginDetails(response.data);
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

      {!isValid &&
        <>
          <Alert variant="danger">
            <p className="mb-0"><strong>We couldn&apos;t find a valid token</strong></p>
            <p className="mb-0">Please try checking the reset email we sent you.</p>
          </Alert>
        </>
      }

      {isValid &&
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
                      type="password"
                      name="password"
                      value={values.password}
                      onChange={handleChange}
                      isValid={touched.password && !errors.password}
                      isInvalid={touched.password && errors.password}
                      size="lg"
                    />
                    {errors.password &&
                      <Form.Control.Feedback type="invalid">{errors.password}</Form.Control.Feedback>
                    }
                  </Form.Group>
                </div>

                <p className="mb-5">
                  <Button size="lg" type="submit" disabled={!isValid || isSubmitting}>Change password</Button>
                </p>
              </Form>
            )}
          </Formik>
        </>
      }

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(ResetPassword);