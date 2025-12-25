import React from "react";
import { Menubar } from "primereact/menubar";

const Topbar: React.FC = () => {
  const items = [
    {
      label: "Salir",
      icon: "pi pi-sign-out",
      command: () => {
        window.location.href = "/";
      },
    },
  ];

  return <Menubar model={items} />;
};

export default Topbar;