import React from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Login from "./pages/Login/Login";
import Catalog from "./pages/Catalog/Catalog";
import Cart from "./pages/Cart/Cart";
import OrderDetail from "./pages/OrderDetail/OrderDetail";
import { AuthProvider, useAuth } from "./context/AuthContext";
import { CartProvider } from "./context/CartContext";
import Sidebar from "./layout/Sidebar";

const PrivateLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user } = useAuth();

  if (!user) {
    return <Navigate to="/login" replace />;
  }

  return (
    <div className="app-layout" style={{ display: "flex" }}>
      <Sidebar />
      <div className="app-content" style={{ flex: 1, padding: "1rem" }}>
        {children}
      </div>
    </div>
  );
};

export default function App() {
  return (
    <AuthProvider>
      <CartProvider>
        <Router>
          <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/catalog" element={<PrivateLayout><Catalog /></PrivateLayout>} />
            <Route path="/cart" element={<PrivateLayout><Cart /></PrivateLayout>} />
            <Route path="/orders/:id" element={<PrivateLayout><OrderDetail /></PrivateLayout>} />
            <Route path="*" element={<Navigate to="/login" replace />} />
          </Routes>
        </Router>
      </CartProvider>
    </AuthProvider>
  );
}