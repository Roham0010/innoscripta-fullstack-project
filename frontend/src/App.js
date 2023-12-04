import "./App.css";
import Header from "./componenets/header/Header";
import { Layout } from "antd";
import { Outlet } from "react-router-dom";
const { Content } = Layout;

const App = () => (
  <div>
    <Header />
    <Content className="container">
      <Outlet />
    </Content>
  </div>
);

export default App;
