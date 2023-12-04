import React from "react";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { ProtectedRoute } from "../utils/auth";
import Home from "../componenets/pages/Home";
import App from "../App";
import { Login } from "../componenets/auth/login/Login";
import { Register } from "../componenets/auth/register/Register";
import { Logout } from "../componenets/auth/Logout";
import Articles from "../componenets/pages/Articles";
import Article from "../componenets/pages/Article";
import Dashboard from "../componenets/pages/Dashboard";

const routes = () => (
  <BrowserRouter>
    <Routes>
      <Route element={<App />}>
        <Route element={<Logout />} path="/logout" />
        <Route element={<Home />} path="/" />
        <Route element={<Login />} path="login" />
        <Route element={<Register />} path="register" />
        <Route path="articles">
          <Route element={<Articles />} path="" />
          <Route element={<Article />} path=":id" />
        </Route>
        <Route element={<ProtectedRoute />} path="dashboard">
          <Route element={<Dashboard />} path="" />
        </Route>
      </Route>
    </Routes>
  </BrowserRouter>
);

export default routes;
