const Login = (state = {}, action) => {
  switch (action.type) {
  case "SET_LOGIN_STATE":
    return {
      ...state,
      [action.key]: action.value
    };
  default:
    return state;
  }
};

export const mapStateToProps = (state) => {
  const { Login } = state;
  return { loginPageType: Login.login_page_type || "login" };
};

export const mapDispatchToProps = (dispatch) => {
  return {
    setType: (type) => dispatch(
      {
        type: "SET_LOGIN_STATE",
        key: "login_page_type",
        value: type,
      })
  };
};

export default Login;
