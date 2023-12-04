import { Menu, Layout } from "antd";
const { Content } = Layout;

const MyContent = ({ children }) => (
  <Content style={{ padding: "0 50px" }}>{children}</Content>
);

export default MyContent;
