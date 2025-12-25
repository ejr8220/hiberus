import React from "react";
import { useCart } from "../../context/CartContext";
import { Button } from "primereact/button";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import api from "../../api/client";
import { useNavigate } from "react-router-dom";
import "./Cart.css";

export default function Cart() {
  const { items, removeFromCart, clearCart } = useCart();
  const navigate = useNavigate();

  const total = items.reduce((sum, i) => sum + i.quantity * i.unitPrice, 0);

  const createOrder = async () => {
    try {
      const payload = {
        customerId: "demo-customer",
        status: "PENDING",
        total: total.toFixed(2),
        items: items.map((i) => ({
          productId: i.productId,
          quantity: i.quantity,
          unitPrice: i.unitPrice.toFixed(2),
        })),
      };

      const res = await api.post("/orders", payload, {
        headers: { "X-Role": "CLIENTE" },
      });

      clearCart();
      navigate(`/orders/${res.data.id}`);
    } catch (error: any) {
      console.error("Error al crear pedido:", error);
      alert(error.response?.data?.message || "No se pudo crear el pedido.");
    }
  };

  const actionBodyTemplate = (rowData: any) => (
    <a
      href="#"
      style={{
        color: '#dc3545',
        textDecoration: 'underline',
        cursor: 'pointer',
        background: 'none',
        border: 'none',
        padding: 0,
        font: 'inherit'
      }}
      onClick={e => {
        e.preventDefault();
        removeFromCart(rowData.productId);
      }}
    >Eliminar</a>
  );

  return (
    <div className="cart-container">
      <h2>Carrito</h2>
      {items.length === 0 ? (
        <p>Tu carrito está vacío.</p>
      ) : (
        <>
          <DataTable value={items} responsiveLayout="scroll" className="p-datatable-striped">
            <Column field="name" header="Producto" />
            <Column field="quantity" header="Cantidad" />
            <Column
              field="unitPrice"
              header="Precio Unitario"
              body={(rowData) => `$${rowData.unitPrice.toFixed(2)}`}
            />
            <Column
              header="Subtotal"
              body={(rowData) => `$${(rowData.quantity * rowData.unitPrice).toFixed(2)}`}
            />
            <Column body={actionBodyTemplate} header="Acciones" />
          </DataTable>

          <div className="cart-total">
            <h3>Total: ${total.toFixed(2)}</h3>
          </div>

          <Button
            label="Confirmar Pedido"
            icon="pi pi-check"
            className="p-button-success"
            onClick={createOrder}
          />
        </>
      )}
    </div>
  );
}