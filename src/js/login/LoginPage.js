
import React from "react";
import * as tenantFunctions from "../classes/Tenant";
import Login from "./Login";

const LoginPage = () => {

  tenantFunctions.setTitle("Login");

  return (

    <>

      <div className="container min-vh-100 mb-n3 overflow-auto">
        <div className="row justify-content-center py-3">
          <div className="col-lg-8 col-md-10">

            <p className="mb-5">
              <a href="/" className="btn btn-outline-primary btn-outline-light-d">Quit</a>
            </p>

            <div className="row align-items-center">
              <div className="col order-2 order-md-1">
                <h1 className="">Login</h1>
                <p className="">Sign in to {tenantFunctions.getName()}</p>
              </div>
              <div className="col-12 col-md-auto order-1 order-md-2">
                {(tenantFunctions.getKey("logo_dir")) ? (
                  <img src={tenantFunctions.getLogoUrl("logo-75.png")} srcSet={`${tenantFunctions.getLogoUrl("logo-75@2x.png")} 2x, ${tenantFunctions.getLogoUrl("logo-75@3x.png")} 3x`} alt="" className="img-fluid" />
                )
                  : (
                    <img src="/img/corporate/scds.png" height="75" width="75" alt="" className="img-fluid" />
                  )}
                <div className="mb-4 d-md-none"></div>
              </div>
            </div>
            <div className="mb-4 d-md-none"></div>
            <div className="mb-5 d-none d-md-block"></div>

            <Login />

            <p>
              Need help? <a href="/about">Get support from {tenantFunctions.getName()}</a>.
            </p>

          </div>
        </div>
      </div>

    </>
  );
};

export default LoginPage;