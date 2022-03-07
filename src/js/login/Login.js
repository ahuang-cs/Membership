
import React, { useState } from "react";
import * as tenantFunctions from "../classes/Tenant";
import { Formik } from "formik";
import * as yup from "yup";
import { Form } from "react-bootstrap";
import { Button } from "react-bootstrap";
import { Link } from "react-router-dom";
import { connect } from "react-redux";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";
import axios from "axios";

const schema = yup.object().shape({
  emailAddress: yup.string().email("Your email address must be valid").required("You must provide an email address"),
  password: yup.string().required("You must provide a password"),
  rememberMe: yup.bool(),
});

const Login = (props) => {

  tenantFunctions.setTitle("Login");

  const [error, setError] = useState(null);

  const onSubmit = async (values, { setSubmitting }) => {
    setSubmitting(true);

    try {

      const response = await axios.post("/api/auth/login/login", {
        email_address: values.emailAddress,
        password: values.password,
      });

      console.log(response.data);

      if (response.data.success) {
        props.setType("twoFactor");
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

            <div className="mb-3">
              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
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

            <Form.Group className="mb-3">
              <Form.Check
                name="rememberMe"
                label="Keep me logged in"
                onChange={handleChange}
                isInvalid={!!errors.rememberMe}
                feedback={errors.rememberMe}
                feedbackType="invalid"
                id="rememberMe"
              />
            </Form.Group>

            <p className="mb-5">
              <Button size="lg" type="submit" disabled={!isValid || isSubmitting}>Login</Button>
            </p>

            <div className="mb-5">
              <p>
                New member? Your club will create an account for you and send you a link to get started.
              </p>
              <span>
                <Link to="/login/forgot-password" className="btn btn-dark">
                  Forgot password?
                </Link>
              </span>
            </div>
          </Form>
        )}
      </Formik>

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(Login);