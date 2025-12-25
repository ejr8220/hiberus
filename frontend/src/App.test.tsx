import React from "react";
import { AuthProvider } from "./context/AuthContext";
import { CartProvider } from "./context/CartContext";
import Login from "./pages/Login/Login";
import Catalog from "./pages/Catalog/Catalog";
import Checkout from "./pages/Checkout";

function App() {
  return (
    <AuthProvider>
      <CartProvider>
        <div>
          <Login />
          <Catalog />
          <Checkout />
        </div>
      </CartProvider>
    </AuthProvider>
  );
}

export default App;