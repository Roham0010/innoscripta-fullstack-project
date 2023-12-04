import { Navigate, Outlet } from "react-router-dom";
import cookie from "cookie";
import baseAPI from "./baseAPI";
import { BASE_URL } from "./consts";

/**
 * Protecting routes from none logged-in users
 */
export const ProtectedRoute = ({ redirectPath = "/", children }) => {
  if (!isSignedIn()) {
    return <Navigate to={redirectPath} replace />;
  }

  return children ? children : <Outlet />;
};

/**
 * Sets up token cookie and localStorage to mark a user logged in.
 */
export function setToken(jwtToken, expiresIn) {
  document.cookie = cookie.serialize("token", jwtToken, {
    maxAge: expiresIn,
    path: "/",
  });
}

/**
 * Returns the GQL auth token, for use in xhr requests, etc.
 */
export function authToken() {
  const { token } = cookie.parse(document.cookie);

  return token;
}

/**
 * Returns true if a sign-in token exists for a user.
 */
export function isSignedIn() {
  return !!authToken();
}

export const register = (name, email, password, password_confirmation) => {
  return baseAPI
    .post("register", {
      name,
      email,
      password,
      password_confirmation,
    })
    .then((res) => res.data)
    .then((res) => {
      if (res.access_token) {
        setToken(res.access_token, res.expires_in);
        document.location.href = BASE_URL;
      }
    });
};

export const login = (email, password) => {
  return baseAPI
    .post("login", {
      email,
      password,
    })
    .then((res) => res.data)
    .then((res) => {
      if (res.access_token) {
        setToken(res.access_token, res.expires_in);
        document.location.href = BASE_URL;
      }
    });
};

/**
 * Removes cookies
 */
export function removeCookies() {
  const cookies = document.cookie.split(";");

  for (let i = 0; i < cookies.length; i += 1) {
    const c = cookies[i];
    const eqPos = c.indexOf("=");
    const name = eqPos > -1 ? c.substr(0, eqPos) : c;
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    document.cookie = `${name}=;path=/;expires=${yesterday.toUTCString()}`;
  }
}

export function signOut() {
  removeCookies();
  document.location.href = BASE_URL + "/login";
}
