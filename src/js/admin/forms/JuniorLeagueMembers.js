// import { Formik, Form as FormikForm } from "formik";
import React, { useEffect } from "react";
import * as yup from "yup";
// import { Button } from "react-bootstrap";
import Form from "../../components/form/Form";
import * as tenantFunctions from "../../classes/Tenant";
import DateInput from "../../components/form/DateInput";
import TextInput from "../../components/form/TextInput";
import Header from "../../components/Header";
import { Card } from "react-bootstrap";

const JuniorLeagueMembers = () => {

  useEffect(() => {
    tenantFunctions.setTitle("Junior and Arena League Members Report");
  }, []);

  return (
    <>
      <Header title="Junior League Members" subtitle="Get a list of members valid for Junior or Arena League" />

      <div className="container-xl">
        <div className="row">
          <div className="col-lg-8">

            <Card>
              <Card.Body>
                <Form
                  initialValues={{
                    minAge: "",
                    maxAge: "",
                    ageOn: "",
                  }}
                  validationSchema={yup.object({
                    minAge: yup.number("You must enter a minimum age").required("You must enter a minimum age").integer("You must enter a whole number").min(0, "You must enter a value greater than zero").max(120, "You must enter a value less than 120"),
                    maxAge: yup.number("You must enter a minimum age").required("You must enter a minimum age").integer("You must enter a whole number").min(0, "You must enter a value greater than zero").max(120, "You must enter a value less than 120").test({
                      name: "max-greater-than-min",
                      exclusive: false,
                      params: {},
                      message: "You must enter a maximum age which is greater than or equal to the minimum age",
                      test: function (value) {
                        return value >= parseInt(this.parent.minAge);
                      },
                    }).test({
                      name: "range-less-than-twenty",
                      exclusive: false,
                      params: {},
                      message: "The range between the minimum age and maximum age must not be greater than twenty",
                      test: function (value) {
                        return (value - parseInt(this.parent.minAge)) <= 20;
                      },
                    }),
                    ageOn: yup.date().required("You must enter a date").min("2000-01-01", "You must enter a date greater than 1 January 2000"),
                  })}
                  onSubmit={(values, { setSubmitting }) => {
                    setTimeout(() => {
                      alert(JSON.stringify(values, null, 2));
                      setSubmitting(false);
                    }, 400);
                  }}
                  submitTitle="View report"
                >
                  {/* <FormikForm> */}

                  <div className="row">
                    <div className="col-md">
                      <TextInput
                        label="Minimum age"
                        name="minAge"
                        type="number"
                        min="0"
                        max="120"
                        step="1"
                        placeholder="8"
                      />
                    </div>

                    <div className="col-md">
                      <TextInput
                        label="Maximum age"
                        name="maxAge"
                        type="number"
                        min="0"
                        max="120"
                        step="1"
                        placeholder="12"
                      />
                    </div>
                  </div>

                  <DateInput
                    label="Age on day"
                    name="ageOn"
                  />

                  {/* <Button type="submit">View report</Button> */}
                  {/* </FormikForm> */}
                </Form>
              </Card.Body>
            </Card>

          </div>
        </div>
      </div>
    </>
  );

};

export default JuniorLeagueMembers;