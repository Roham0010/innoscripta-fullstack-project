import { useState } from "react";
import { register } from "../../../utils/auth";
import { handleErrors } from "../../../utils/handelingErrors";

export const Register = () => {
  const [email, setEmail] = useState("");
  const [name, setName] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [error, setError] = useState("");
  const submit = (e) => {
    e.preventDefault();
    if (password !== passwordConfirmation) {
      setError("Passwords does not match");
      return;
    }

    register(name, email, password, passwordConfirmation).catch((e) =>
      handleErrors(e, setError)
    );
  };

  return (
    <div class="row center-block justify-content-center py-250p">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <form onSubmit={submit}>
          <h1 class="h3 mb-3 fw-normal">Please registerd</h1>

          <div class="form-floating py-1">
            <input
              value={name}
              onChange={(e) => setName(e.target.value)}
              class="form-control"
              name="text"
              placeholder="Roham"
              type="text"
              required
            />
            <label for="floatingInput">Name</label>
          </div>
          <div class="form-floating py-1">
            <input
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              class="form-control"
              name="email"
              placeholder="name@example.com"
              type="email"
              required
            />
            <label for="floatingInput">Email address</label>
          </div>
          <div class="form-floating py-1">
            <input
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              class="form-control"
              placeholder="Password"
              type="password"
              required
            />
            <label for="floatingPassword">Password</label>
          </div>
          <div class="form-floating py-1">
            <input
              value={passwordConfirmation}
              onChange={(e) => setPasswordConfirmation(e.target.value)}
              class="form-control"
              placeholder="Password confirmation"
              type="password"
              required
            />
            <label for="floatingPassword">Password confirmation</label>
          </div>

          {error && (
            <div class="form-text" id="basic-addon4">
              {error}
            </div>
          )}
          <button class="btn btn-primary w-100 py-2 mt-2" type="submit">
            Register
          </button>
        </form>
      </div>
    </div>
  );
};
