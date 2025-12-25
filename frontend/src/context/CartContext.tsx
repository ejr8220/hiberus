import React, { createContext, useContext, useState } from "react";

export interface CartItem {
  productId: number;
  name: string;
  unitPrice: number;
  quantity: number;
  stock: number;
}

interface CartContextType {
  items: CartItem[];
  addToCart: (product: CartItem) => void;
  removeFromCart: (productId: number) => void;
  clearCart: () => void;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [items, setItems] = useState<CartItem[]>([]);

  const addToCart = (product: CartItem) => {
    setItems((prev) => {
      const existing = prev.find((p) => p.productId === product.productId);
      if (existing) {
        
        const desired = existing.quantity + 1;
        const capped = Math.min(desired, product.stock);
        return prev.map((p) =>
          p.productId === product.productId ? { ...p, quantity: capped } : p
        );
      }
      if (product.stock <= 0) return prev; 
      return [...prev, { ...product, quantity: 1 }];
    });
  };

  const removeFromCart = (productId: number) => {
    setItems((prev) => prev.filter((p) => p.productId !== productId));
  };

  const clearCart = () => {
    setItems([]);
  };

  return (
    <CartContext.Provider value={{ items, addToCart, removeFromCart, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => {
  const ctx = useContext(CartContext);
  if (!ctx) {
    throw new Error("useCart debe usarse dentro de un CartProvider");
  }
  return ctx;
};