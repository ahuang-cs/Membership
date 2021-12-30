import { combineReducers } from 'redux'
import GlobalSettings from './GlobalSettings'

const allReducers = combineReducers({
  "SKIPCLEAR/GlobalSettings": GlobalSettings
});

export default allReducers;