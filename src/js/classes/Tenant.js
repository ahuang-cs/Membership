// import reducer from "../reducers/index";
// import { getState } from 'redux';
import store from "../reducers/store";

export function getKey(key) {
  const state = store.getState();
  return state["SKIPCLEAR/GlobalSettings"][key];
}

export function setTitle(title) {
  document.title = title + " - " + getKey("club_name");
}