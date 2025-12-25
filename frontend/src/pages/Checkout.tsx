import React, { useContext } from "react";
import api from "../api/client";
import { useCart } from "../context/CartContext";
import { useAuth } from "../context/AuthContext";

export default function Checkout() {
  const { items, clearCart } = useCart();
  const { user } = useAuth();  // ✅ ahora sí dentro del componente

  const handleCheckout = async () => {
    const payload = {
      user,
      items: items.map((i: any) => ({
        productId: i.product?.id ?? i.productId,
        quantity: i.quantity,
      })),
    };
    await api.post("/orders", payload);
    clearCart();
    alert("Orden creada!");
  };

  return (
    <div>
      <h2>Checkout</h2>
      <button onClick={handleCheckout}>Confirmar Orden</button>
    </div>
  );
}