import React, { useEffect, useState, useRef } from "react";
// import { useParams } from "react-router-dom";
import api from "../../api/client";
import { Toast } from "primereact/toast";
import { Button } from "primereact/button";

export default function OrderDetail() {
  const [orderId, setOrderId] = useState("");
  const [order, setOrder] = useState<any>(null);
  const [loading, setLoading] = useState(false);
  const toast = useRef<Toast>(null);

  const loadOrder = async (id: string) => {
    setLoading(true);
    setOrder(null);
    try {
      const res = await api.get(`/orders/${id}`, {
        headers: { "X-Role": "CLIENTE"}
      });
      setOrder(res.data);
    } catch (error: any) {
      setOrder(null);
      alert(error.response?.data?.message || "No se pudo cargar el pedido.");
    } finally {
      setLoading(false);
    }
  };

  const checkout = async () => {
    try {
      const res = await api.post(`/orders/${orderId}/checkout`, {}, {
        headers: { "X-Role": "CLIENTE", "X-Customer-Id": "demo-customer" }
      });
      setOrder({ ...order, status: res.data.status, total: res.data.total ?? order.total });
      toast.current?.show({
        severity: "success",
        summary: "Pago realizado",
        detail: `Pedido #${orderId} actualizado a ${res.data.status}`,
        life: 3000
      });
    } catch (error: any) {
      console.error("Error en checkout:", error);
      alert(error.response?.data?.message || "No se pudo procesar el pago.");
    }
  };

  return (
    <div className="order-detail-container">
      <Toast ref={toast} position="bottom-right" />
      <h2>Buscar Pedido</h2>
      <div style={{ marginBottom: 16 }}>
        <input
          type="text"
          placeholder="Número de orden"
          value={orderId}
          onChange={e => setOrderId(e.target.value)}
          style={{ marginRight: 8 }}
        />
        <Button label="Buscar" onClick={() => loadOrder(orderId)} disabled={!orderId || loading} />
      </div>
      {loading && <div>Cargando...</div>}
      {order && (
        <>
          <h2>Pedido #{order.id}</h2>
          <p><strong>Estado:</strong> {order.status}</p>
          <p><strong>Total:</strong> {Number(order.total).toFixed(2)}</p>
          <h3>Items</h3>
          <ul>
            {order.items.map((it: any) => (
              <li key={it.productId}>
                {it.name} x {it.quantity} @ {Number(it.unitPrice).toFixed(2)}
              </li>
            ))}
          </ul>
          {order.status === "PENDING" && (
            <Button
              label="Checkout"
              icon="pi pi-credit-card"
              onClick={checkout}
              className="p-button-success"
            />
          )}
        </>
      )}
    </div>
  );
}