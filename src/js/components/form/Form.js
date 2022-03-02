/**
 * Form component
 */

import React from "react";
import { Formik, Form as FormikForm } from "formik";
import Button from "react-bootstrap/Button";

const Form = (props) => {

  return (
    <>
      <Formik
        {...props}
      >
        {({ isSubmitting }) => (
          <FormikForm>
            {props.children}

            <div>
              <Button variant="secondary" type="reset" disabled={isSubmitting}>
                Clear
              </Button>{" "}

              <Button variant="primary" type="submit" disabled={isSubmitting}>
                Submit
              </Button>
            </div>
          </FormikForm>
        )}
      </Formik>
    </>
  );
};

export default Form;