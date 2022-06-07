// import { Formik, Form as FormikForm } from "formik";
import React, { useEffect, useState } from "react";
import * as yup from "yup";
// import { Button } from "react-bootstrap";
import Form from "../../components/form/Form";
import * as tenantFunctions from "../../classes/Tenant";
import DateInput from "../../components/form/DateInput";
import TextInput from "../../components/form/TextInput";
import Header from "../../components/Header";
import Breadcrumb from "../../components/Breadcrumb";
import { Alert, Card, ListGroup } from "react-bootstrap";
import axios from "axios";
import moment from "moment";
import { useRegistration } from "@web-auth/webauthn-helper";

const WebAuthn = () => {

  const [error, setError] = useState(null);

  useEffect(() => {
    tenantFunctions.setTitle("Web Authentication");
  }, []);

  const register = useRegistration({
    actionUrl: "/api/my-account/webauthn/register",
    optionsUrl: "/api/my-account/webauthn/challenge",
  });

  const handleRegister = async () => {
    try {
      const response = await register({
        // username: "chris.heppell@myswimmingclub.uk",
        // displayName: "Chris Heppell",
      });
      console.log(response);
    } catch (error) {
      setError(error.message);
      console.log("OOPS", error);
    }
  };

  const crumbs = [
    {
      to: "/my-account",
      title: "My Account",
      name: "My Account",
    },
    {
      to: "/my-account/webauthn",
      title: "WebAuthn",
      name: "WebAuthn",
    },
  ];

  return (
    <>
      <Header breadcrumbs={<Breadcrumb crumbs={crumbs} />} title="WebAuthn" subtitle="Passwordless login for your club account" />

      <div className="container-xl">
        <div className="row">
          <div className="col-lg-8">

            {error &&
              <Alert variant="danger">
                {error}
              </Alert>
            }

            <button onClick={handleRegister}>Register</button>

          </div>
        </div>
      </div>
    </>
  );

};

export default WebAuthn;