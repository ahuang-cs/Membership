/**
 * Suspense fallback
 */

import React from "react";
// import Placeholder from 'react-bootstrap/Placeholder';
import { Placeholder, Breadcrumb } from "react-bootstrap";
import Header from "../components/Header";

const SuspenseFallback = () => {

  const title = (
    <Placeholder animation="glow">
      <Placeholder xs={4} />
    </Placeholder>
  );

  const subtitle = (
    <Placeholder animation="glow">
      <Placeholder xs={7} />
    </Placeholder>
  );

  return (

    <>

      <Header title={title} subtitle={subtitle} />

      <div className="container-xl">
        <div className="row">
          <div className="col-lg-8">
            <p aria-hidden="true" className="lead">
              <Placeholder animation="glow">
                <Placeholder xs={4} /> <Placeholder xs={5} /> <Placeholder xs={3} />{" "}
                <Placeholder xs={3} /> <Placeholder xs={4} /> <Placeholder xs={2} />{" "}
                <Placeholder xs={3} /> <Placeholder xs={1} /> <Placeholder xs={4} />{" "}
                <Placeholder xs={4} />
              </Placeholder>
            </p>

            <p aria-hidden="true">
              <Placeholder animation="glow">
                <Placeholder xs={5} /> <Placeholder xs={2} /> <Placeholder xs={3} />{" "}
                <Placeholder xs={4} /> <Placeholder xs={3} /> <Placeholder xs={1} />{" "}
                <Placeholder xs={3} /> <Placeholder xs={3} /> <Placeholder xs={2} />{" "}
                <Placeholder xs={1} />
              </Placeholder>
            </p>

            <p aria-hidden="true">
              <Placeholder animation="glow">
                <Placeholder xs={7} /> <Placeholder xs={4} /> <Placeholder xs={4} />{" "}
                <Placeholder xs={6} /> <Placeholder xs={8} />
              </Placeholder>
            </p>

            <p aria-hidden="true">
              <Placeholder animation="glow">
                <Placeholder xs={5} /> <Placeholder xs={3} /> <Placeholder xs={7} />{" "}
                <Placeholder xs={4} /> <Placeholder xs={6} />
              </Placeholder>
            </p>
          </div>
        </div>
      </div>

    </>
  );
};

export default SuspenseFallback;