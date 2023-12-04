import axios from "axios";
import { authToken, signOut } from "./auth";
import { BASE_API_URL } from "./consts";

const request = axios.create({
  baseURL: BASE_API_URL,
  headers: {
    "Content-type": "application/json",
    Authorization: "Bearer " + authToken(),
  },
});

request.interceptors.response.use(
  (res) => res,
  function (err) {
    if (err.response.status === 401) {
      signOut();
    }
  }
);

export default request;
