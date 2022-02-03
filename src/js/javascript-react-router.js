import React, { Suspense } from "react";
import { render } from "react-dom";
import { Provider } from "react-redux";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import SuspenseFallback from "./views/SuspenseFallback";
import ScrollToTop from "./components/global/ScrollToTop";
import store from "./reducers/store";
import AppWrapper from "./views/AppWrapper";

const NotifyHome = React.lazy(() => import("./notify/pages/Home"));
const NotifyComposer = React.lazy(() => import("./notify/forms/Composer"));
const NotifySuccess = React.lazy(() => import("./notify/forms/Composer"));
const GalasDefaultPage = React.lazy(() => import("./galas/forms/GalasDefaultPage"));
const GalaHomePage = React.lazy(() => import("./galas/forms/GalaHome"));
const NotFound = React.lazy(() => import("./views/NotFound"));
const AboutReactApp = React.lazy(() => import("./pages/AboutReactApp"));

const rootElement = document.getElementById("root");
render(
  <Provider store={store}>
    <AppWrapper>
      <BrowserRouter>
        <ScrollToTop />
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
    </AppWrapper>
  </Provider>,
  rootElement
);
