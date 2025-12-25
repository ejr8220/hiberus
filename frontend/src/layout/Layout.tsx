import React from "react";
import { Outlet } from "react-router-dom";
import Topbar from "./Topbar";
import Sidebar from "./Sidebar";
import "./layout.css";

const Layout: React.FC = () => {
  return (
    <div className="layout">
      <Topbar />
      <div className="layout-body">
        <Sidebar />
        <div className="layout-content">
          <Outlet />
        </div>
      </div>
    </div>
  );
};

export default Layout;