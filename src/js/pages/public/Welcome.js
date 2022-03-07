import React from "react";
import * as tenantFunctions from "../../classes/Tenant";
import { Link } from "react-router-dom";

const Welcome = () => {

  tenantFunctions.setTitle("About this software");

  return (

    <>

      <div className="container-xl">


        <h1 className="mb-5">Welcome to the {tenantFunctions.getName()} Membership System</h1>

        <div className="row">
          <div className="col-lg-8">



            <h2>Already registered?</h2>
            <p className="lead">
              Log in to your account now
            </p>
            <p className="mb-5">
              <Link className="btn btn-lg btn-primary" to="/login">
                Login
              </Link>
            </p>
            <h2>COVID-19 Contact Tracing</h2>
            <p className="lead">
              Check in online if you&apos;ve been asked to fill out a contact tracing form
            </p>
            <p className="mb-5">
              <a className="btn btn-lg btn-primary" href="https://testclub.mt.myswimmingclub.uk/covid/contact-tracing">
                Check In
              </a>
            </p>
            <h2>Not got an account?</h2>
            <p className="lead">
              Your club will create your account.
            </p>
            <p className="mb-5">
              If you&apos;ve just joined, the person handling your application will be in touch soon.
            </p>


          </div>
        </div>
      </div>

    </>
  );
};

export default Welcome;