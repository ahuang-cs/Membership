
import React, { useState } from "react";
import * as tenantFunctions from "../classes/Tenant";
import { Formik } from "formik";
import * as yup from "yup";
import { Alert, Form } from "react-bootstrap";
import { Button } from "react-bootstrap";
import { connect } from "react-redux";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";
import axios from "axios";

const schema = yup.object().shape({
  authCode: yup.string().length(6, "Authentiction codes are 6 digits").required("You must enter an authentication code"),
  setUpTwoFactor: yup.bool(),
});

const onSubmit = (ev) => {
  console.log(ev);
};

const TwoFactor = (props) => {

  const [enableResend, setEnableResend] = useState(true);
  const [resent, setResent] = useState(false);

  const handleResend = async () => {
    setEnableResend(false);

    // Make API request
    const response = await axios.post("/api/auth/login/resend-two-factor", {
    });

    if (response.data.success) {
      setResent(true);
      props.setLoginDetail("two_factor_method", "email");
    } else {
      // Show error
    }

    new Promise(function () {
      setTimeout(function () {
        setEnableResend(true);
      }, 5000);
    });
  };

  tenantFunctions.setTitle("Confirm Login");

  let helpString = "We've sent a confirmation code to your registered email address";
  let helpResendString = "Resend email";
  if (props.two_factor_method === "totp") {
    helpString = "We've sent a confirmation code to your registered email address";
    helpResendString = "Resend email";
  }

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

            {!props.has_totp &&
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
                {helpString}
              </p>
              <span>
                <Button
                  variant="dark"
                  onClick={handleResend}
                  disabled={!enableResend}
                >
                  {helpResendString}
                </Button>
                {
                  resent &&
                  <Alert variant="info" className="mt-2">
                    We&apos;ve resent your authentication code.
                  </Alert>
                }
              </span>
            </div>
          </Form>
        )}
      </Formik>

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(TwoFactor);