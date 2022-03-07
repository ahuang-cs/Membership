
import React, { useEffect } from "react";
import * as tenantFunctions from "../classes/Tenant";
import Login from "./Login";
import TwoFactor from "./TwoFactor";
import { connect } from "react-redux";
import Logo from "../components/Logo";
import { mapStateToProps, mapDispatchToProps } from "../reducers/Login";
import { Link } from "react-router-dom";

const titles = {
  login: {
    heading: "Login",
    subheading: "Sign in to " + tenantFunctions.getName(),
  },
  twoFactor: {
    heading: "Confirm it's you",
    subheading: "Enter your two-factor authentication code",
  },
};

const LoginPage = (props) => {

  tenantFunctions.setTitle("Login");

  useEffect(() => {
    props.setType("login");

    return () => {
      // props.dispatch
    };
  }, []);

  return (

    <>

      <div className="container min-vh-100 mb-n3 overflow-auto">
        <div className="row justify-content-center py-3">
          <div className="col-lg-8 col-md-10">

            <p className="mb-5">
              <Link to="/" className="btn btn-outline-primary btn-outline-light-d">Quit</Link>
            </p>

            <div className="row align-items-center">
              <div className="col order-2 order-md-1">
                <h1 className="">{titles[props.loginPageType].heading}</h1>
                <p className="">{titles[props.loginPageType].subheading}</p>
              </div>
              <div className="col-12 col-md-auto order-1 order-md-2">
                <Logo />
                <div className="mb-4 d-md-none"></div>
              </div>
            </div>
            <div className="mb-4 d-md-none"></div>
            <div className="mb-5 d-none d-md-block"></div>

            {props.loginPageType === "login" &&
              <Login />
            }

            {props.loginPageType === "twoFactor" &&
              <TwoFactor />
            }

            <p>
              Need help? <a href="/about">Get support from {tenantFunctions.getName()}</a>.
            </p>

          </div>
        </div>
      </div>

    </>
  );
};

export default connect(mapStateToProps, mapDispatchToProps)(LoginPage);