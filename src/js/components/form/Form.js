/**
 * Form component
 */

import React from "react";
import { Formik, Form as FormikForm, useFormikContext } from "formik";
import Button from "react-bootstrap/Button";

const SubmissionButtons = (props) => {

  const { isSubmitting, dirty, isValid, errors } = useFormikContext();

  console.log(errors);

  return (
    <>
      {
        false && errors &&
        <p className="text-end text-danger">
          There are <strong>{Object.keys(errors).length} errors</strong>
        </p>
      }
      <div className="row">
        <div className="col-auto ms-auto">
          {!props.hideClear &&
            <>
              <Button variant="secondary" type="reset" disabled={isSubmitting || !dirty}>
                {props.clearTitle || "Clear"}
              </Button>{" "}
            </>
          }

          <Button variant="primary" type="submit" disabled={!dirty || !isValid || isSubmitting}>
            {props.submitTitle || "Submit"}
          </Button>
        </div>
      </div>
    </>
  );
};

const Form = (props) => {

  const {
    initialValues,
    validationSchema,
    onSubmit,
    submitTitle,
    hideClear,
    clearTitle,
    ...otherProps
  } = props;

  return (
    <>
      <Formik
        initialValues={initialValues}
        validationSchema={validationSchema}
        onSubmit={onSubmit}
      >
        <FormikForm {...otherProps}>
          {props.children}

          <SubmissionButtons
            submitTitle={submitTitle}
            hideClear={hideClear}
            clearTitle={clearTitle}
          />
        </FormikForm>
      </Formik>
    </>
  );
};

export default Form;