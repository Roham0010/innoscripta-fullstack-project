import { removeCookies } from "../../utils/auth";
import { BASE_URL } from "../../utils/consts";

export const Logout = () => {
  removeCookies();
  document.location.href = BASE_URL;
};
