import { NavLink } from "react-router-dom";
import { isSignedIn } from "../../utils/auth";
import logo from "../../assets/logo.svg";

const Header = () => (
  <nav class="navbar navbar-expand-lg px-5">
    <div className="container">
      <img src={logo} alt="logo" width="45" className="logo " />
      <NavLink className="navbar-brand nav-link px-4" to="/">
        {/* <div className="logo-container"> */}
        {/* </div> */}
        Readify
      </NavLink>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div
          class="collapse navbar-collapse justify-content-end"
          id="navbarSupportedContent"
        >
          <ul class="navbar-nav ">
            {!isSignedIn() && (
              <>
                <li class="nav-item">
                  <NavLink className="nav-link" to="/register">
                    Register
                  </NavLink>
                </li>

                <li class="nav-item">
                  <NavLink className="nav-link" to="/login">
                    Login
                  </NavLink>
                </li>
              </>
            )}
            {isSignedIn() && (
              <>
                <li class="nav-item">
                  <NavLink className="nav-link" to="/dashboard">
                    Dashboard
                  </NavLink>
                </li>
                <li class="nav-item">
                  <NavLink className="nav-link" to="/logout">
                    Logout
                  </NavLink>
                </li>
              </>
            )}
          </ul>
        </div>
      </div>
    </div>
  </nav>
);

export default Header;
