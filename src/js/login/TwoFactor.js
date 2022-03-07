
import React from "react";
import * as tenantFunctions from "../classes/Tenant";
import { Formik } from "formik";
import * as yup from "yup";
import { Form } from "react-bootstrap";
import { Button } from "react-bootstrap";
import { connect } from "react-redux";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";

const schema = yup.object().shape({
  authCode: yup.string().length(6, "Authentiction codes are 6 digits").required("You must enter an authentication code"),
  setUpTwoFactor: yup.bool(),
});

const onSubmit = (ev) => {
  console.log(ev);
};

const handleResend = (ev) => {
  console.log(ev);
};

const TwoFactor = () => {

  tenantFunctions.setTitle("Confirm Login");

  return (

    <>

      {/* ERRORS GO HERE */}

      {/* ACCOUNT LOCKED */}

      <Formik
        validationSchema={schema}
        onSubmit={onSubmit}
        initialValues={{
          authCode: "",
          setUpTwoFactor: false,
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
              <Form.Group controlId="password">
                <Form.Label>Authentication code</Form.Label>
                <Form.Control
                  name="authCode"
                  autofocus
                  value={values.authCode}
                  onChange={handleChange}
                  isValid={touched.authCode && !errors.authCode}
                  isInvalid={touched.authCode && errors.authCode}
                  size="lg"
                  placeholder="654321"
                  pattern="[0-9]*"
                  inputMode="numeric"
                  autoComplete="one-time-code"
                />
                {errors.authCode &&
                  <Form.Control.Feedback type="invalid">{errors.authCode}</Form.Control.Feedback>
                }
              </Form.Group>
            </div>

            {true &&
              <Form.Group className="mb-3">
                <Form.Check
                  name="setUpTwoFactor"
                  label="Set up an authenticator app"
                  onChange={handleChange}
                  isInvalid={!!errors.setUpTwoFactor}
                  feedback={errors.setUpTwoFactor}
                  id="setUpTwoFactor"
                />
              </Form.Group>
            }

            <p className="mb-5">
              <Button size="lg" type="submit" disabled={!isValid || isSubmitting}>Confirm login</Button>
            </p>

            <div className="mb-5">
              <p>
                New member? Your club will create an account for you and send you a link to get started.
              </p>
              <span>
                <Button
                  variant="dark"
                  onClick={handleResend}
                >
                  Send/Resend
                </Button>
              </span>
            </div>
          </Form>
        )}
      </Formik>

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(TwoFactor);