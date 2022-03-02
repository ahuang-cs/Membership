/**
 * League members form
 */

import React from "react";
// import Placeholder from 'react-bootstrap/Placeholder';
import Header from "../../components/Header";
import Form from "../../components/form/Form";
import { Form as BSForm } from "react-bootstrap";

const LeagueMembers = () => {

  return (
    <>
      <Header title="League Qualifiers" subtitle="Get a list of members eligible for Junior League or Arena League" />

      <div className="container-xl">
        <div className="row">
          <div className="col-lg-8">
            <Form>

              <BSForm.Group as={Col} md="4" controlId="validationFormik01">
                <BSForm.Label>First name</BSForm.Label>
                <BSForm.Control
                  type="text"
                  name="firstName"
                  value={values.firstName}
                  onChange={handleChange}
                  isValid={touched.firstName && !errors.firstName}
                />
                <BSForm.Control.Feedback>Looks good!</BSForm.Control.Feedback>
              </BSForm.Group>

            </Form>
          </div>
        </div>
      </div>
    </>
  );
};

export default LeagueMembers;