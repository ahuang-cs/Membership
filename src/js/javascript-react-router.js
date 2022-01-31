import React, { Suspense } from "react";
import { render } from "react-dom";
import { Provider } from "react-redux";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import SuspenseFallback from "./views/SuspenseFallback";
import axios from "axios";
import store from "./reducers/store";

const NotifyHome = React.lazy(() => import("./notify/pages/Home"));
const NotifyComposer = React.lazy(() => import("./notify/forms/Composer"));
const NotifySuccess = React.lazy(() => import("./notify/forms/Composer"));
const GalasDefaultPage = React.lazy(() => import("./galas/forms/GalasDefaultPage"));
const GalaHomePage = React.lazy(() => import("./galas/forms/GalaHome"));
const NotFound = React.lazy(() => import("./views/NotFound"));
const AboutReactApp = React.lazy(() => import("./pages/AboutReactApp"));

axios.get("/api/settings/tenant")
  .then(response => {
    let data = response.data;

    store.dispatch({
      type: "ADD_TENANT_KEYS",
      payload: data.keys,
    });

    store.dispatch({
      type: "ADD_TENANT_DETAILS",
      payload: data.tenant,
    });
  })
  .catch(function (error) {
    console.log(error);
  });

axios.get("/api/settings/user")
  .then(response => {
    let data = response.data;

    store.dispatch({
      type: "ADD_USER_KEYS",
      payload: data.keys,
    });

    store.dispatch({
      type: "ADD_USER_DETAILS",
      payload: data.user,
    });
  })
  .catch(function (error) {
    console.log(error);
  });

const rootElement = document.getElementById("root");
render(
  <Provider store={store}>
    <BrowserRouter>
      <Suspense fallback={<SuspenseFallback />}>
        <Routes>
          <Route path="/suspense" element={<SuspenseFallback />} />
          <Route path="/galas" element={<GalaHomePage />}>
            <Route path=":galaId/events" element={<GalasDefaultPage />} />
            <Route path="enter-gala" element={<GalasDefaultPage />} />
          </Route>
          <Route path="/notify" element={<NotifyHome />} />
          <Route path="/notify/new" element={<NotifyComposer />} />
          <Route path="/notify/new/success" element={<NotifySuccess />} />
          <Route path="/about" element={<AboutReactApp />} />
          <Route
            path="*"
            element={<NotFound />}
          />
        </Routes>
      </Suspense>
    </BrowserRouter>
  </Provider>,
  rootElement
);
