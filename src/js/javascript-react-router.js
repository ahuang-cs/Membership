import React from "react";
import { render } from "react-dom";
import { createStore } from 'redux'
import { Provider } from 'react-redux'
import allReducers from './reducers'
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { Composer as NotifyComposer } from "./notify/forms/Composer";
import { Success as NotifySuccess } from "./notify/forms/Success";
import GalasDefaultPage from "./galas/forms/GalasDefaultPage";
import GalaHomePage from "./galas/forms/GalaHome";
import { NotFound } from "./views/NotFound";
import axios from "axios";

const store = createStore(
  allReducers,
  window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
);

store.dispatch({
  type: 'ADD_KEY',
  key: 'TEST_KEY',
  value: 'HEY!',
})

axios.get('/api/settings/tenant')
  .then(response => {
    let data = response.data;

    store.dispatch({
      type: 'ADD_KEYS',
      payload: data,
    });
  })
  .catch(function (error) {
    console.log(error);
  })

const rootElement = document.getElementById("root");
render(
  <Provider store={store}>
    <BrowserRouter>
      <Routes>
        <Route path="/galas" element={<GalaHomePage />}>
          <Route path=":galaId/events" element={<GalasDefaultPage />} />
          <Route path="enter-gala" element={<GalasDefaultPage />} />
        </Route>
        <Route path="/notify/new" element={<NotifyComposer />}>
          {/* <Route path="expenses" element={<Expenses />} />
        <Route path="invoices" element={<Invoices />} /> */}
        </Route>
        <Route path="/notify/new/success" element={<NotifySuccess />}>
        </Route>
        <Route
          path="*"
          element={<NotFound />}
        />
      </Routes>
    </BrowserRouter>
  </Provider>,
  rootElement
);
