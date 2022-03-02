
import React from "react";
import * as tenantFunctions from "../classes/Tenant";

const Login = () => {

  tenantFunctions.setTitle("Login");

  return (

    <>

      {/* ERRORS GO HERE */}

      {/* ACCOUNT LOCKED */}

      <form name="loginform" id="loginform" className="needs-validation" noValidate>
        <div className="mb-3">
          <label className="form-label" htmlFor="email-address">Email address</label>
          <input type="email" name="email-address" id="email-address" className="form-control form-control-lg text-lowercase" placeholder="yourname@example.com" autoComplete="email" />
          <div className="invalid-feedback">
            Please enter a valid email address.
          </div>
        </div>
        <div className="mb-3">
          <label className="form-label" htmlhtmlFor="password">Password</label>
          <input type="password" name="password" id="password" className="form-control form-control-lg" required placeholder="Password" autoComplete="current-password" />
          <div className="invalid-feedback">
            Please enter a password.
          </div>
        </div>
        <div className="mb-3">
          <div className="form-check">
            <input className="form-check-input" type="checkbox" name="RememberMe" id="RememberMe" checked aria-describedby="RememberMeHelp" value="1" />
            <label className="form-check-label" htmlFor="RememberMe">Keep me logged in</label>
            <small id="RememberMeHelp" className="form-text text-muted">
              Untick this box if you are using a public or shared computer
            </small>
          </div>
        </div>
        <input type="hidden" name="target" value="" />
        <p className="mb-5">
          <input type="submit" name="login" id="login" value="Login" className="btn btn-lg btn-primary" />
        </p>
        <div className="mb-5">
          <p>
            New member? Your club will create an account for you and send you a link to get started.
          </p>
          <span>
            <a href="/resetpassword" className="btn btn-dark">
              Forgot password?
            </a>
          </span>
        </div>
      </form>

    </>
  );
};

export default Login;