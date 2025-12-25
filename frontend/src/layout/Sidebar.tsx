import React from "react";
import { useAuth } from "../context/AuthContext";
import { PanelMenu } from "primereact/panelmenu";
import { useNavigate } from "react-router-dom";
import "./layout.css";

const Sidebar: React.FC = () => {
  const navigate = useNavigate();
  const { logout } = useAuth();

  const items = [
    {
      label: "Catálogo",
      icon: "pi pi-list",
      command: () => navigate("/catalog"), 
    },
    {
      label: "Carrito",
      icon: "pi pi-shopping-cart",
      command: () => navigate("/cart"), 
    },
    {
      label: "Pedidos",
      icon: "pi pi-credit-card",
      command: () => navigate("/orders/1"), 
    },
    {
      label: "Salir",
      icon: "pi pi-sign-out",
      command: () => logout(),
    },
  ];

  return <PanelMenu model={items} style={{ width: "250px" }} />;
};

export default Sidebar;