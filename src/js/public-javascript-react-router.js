import React, { Suspense } from "react";
import { render } from "react-dom";
import { Provider } from "react-redux";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import SuspenseFallback from "./views/SuspenseFallback";
import ScrollToTop from "./components/global/ScrollToTop";
import store from "./reducers/store";
import PublicAppWrapper from "./views/PublicAppWrapper";
import { PublicNotFound } from "./views/PublicNotFound";
import { NotFound } from "./views/NotFound";
import PublicAppFooter from "./views/PublicAppFooter";

const Welcome = React.lazy(() => import("./pages/public/Welcome"));
const LoginPage = React.lazy(() => import("./login/LoginPage"));

const rootElement = document.getElementById("root");
render(
  <Provider store={store}>
    <BrowserRouter>
      <ScrollToTop />
      <Suspense fallback={<SuspenseFallback />}>
        <Routes>
          <Route path="/login" element={<PublicAppFooter />}>
            <Route index element={<LoginPage />} />
          </Route>
          <Route path="/" element={<PublicAppWrapper />}>
            <Route index element={<Welcome />} />
            <Route path="404" element={<NotFound />} />
          </Route>
          <Route
            path="*"
            element={<PublicNotFound />}
          />
        </Routes>
      </Suspense>
    </BrowserRouter>
  </Provider>,
  rootElement
);
