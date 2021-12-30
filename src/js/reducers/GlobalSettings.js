const GlobalSettings = (state = {}, action) => {
  switch (action.type) {
    case 'ADD_KEY':
      return {
        ...state,
        [action.key]: action.value
      }
    case 'ADD_KEYS':
      return {
        ...state,
        ...action.payload,
      }
    case 'UPDATE_KEY':
      return {
        ...state,
        [action.key]: action.value
      }
    default:
      return state
  }
}

export default GlobalSettings
