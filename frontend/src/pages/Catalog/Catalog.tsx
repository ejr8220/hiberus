import React, { useEffect, useState, useRef } from "react";
import { DataTable } from "primereact/datatable";
import { Column } from "primereact/column";
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";
import { InputText } from "primereact/inputtext";
import { InputNumber } from "primereact/inputnumber";
import { Toast } from "primereact/toast";
import api from "../../api/client";
import { Product } from "../../types";
import "./Catalog.css";
import { useCart } from "../../context/CartContext";

export default function Catalog() {
  const [products, setProducts] = useState<Product[]>([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [modalVisible, setModalVisible] = useState(false);
  const [formData, setFormData] = useState<Partial<Product>>({});
  const [userRole, setUserRole] = useState<"ADMIN" | "CLIENTE">("CLIENTE");
  const toast = useRef<Toast>(null);

  const { addToCart, items } = useCart();

  useEffect(() => {
    loadProducts();
    const role = localStorage.getItem("role") as "ADMIN" | "CLIENTE" | null;
    if (role) setUserRole(role);
  }, []);

  const loadProducts = async (search: string = "") => {
    const res = await api.get("/products", { params: { search } });
    setProducts(res.data.data);
  };

  const openModal = () => {
    setFormData({});
    setModalVisible(true);
  };

  const validateForm = (): boolean => {
    if (!formData.name || formData.name.trim() === "") {
      alert("El nombre no puede estar en blanco.");
      return false;
    }

    const price = Number(formData.price);
    if (isNaN(price) || price <= 0) {
      alert("El precio debe ser un número decimal mayor que 0.");
      return false;
    }

    return true;
  };

  const saveProduct = async () => {
    if (!validateForm()) return;

    try {
      await api.post("/products", formData, {
        headers: { "X-Role": "ADMIN" }
      });

      
      setModalVisible(false);
      loadProducts(searchTerm);

      toast.current?.show({
        severity: "success",
        summary: "Éxito",
        detail: "Registro guardado con éxito",
        life: 3000
      });
    } catch (error: any) {
      console.error("Error al guardar producto:", error);
      const backendMessage =
        error.response?.data?.message || "No se pudo guardar el producto.";
      alert(backendMessage);
    }
  };

  return (
    <div className="catalog-container">
      <Toast ref={toast} position="bottom-right" />

      <div className="catalog-header">
        <h2>Catálogo de Productos</h2>

        <InputText
          placeholder="Buscar..."
          value={searchTerm}
          onChange={(e) => {
            setSearchTerm(e.target.value);
            loadProducts(e.target.value);
          }}
          className="search-input"
        />

        <Button
          label={"Crear Nuevo"}
          icon="pi pi-plus"
          onClick={openModal}
          disabled={userRole !== "ADMIN"}
        />
      </div>

      <DataTable
        value={products}
        paginator
        rows={10}
        responsiveLayout="scroll"
        stripedRows
        className="catalog-table"
      >
        <Column
          field="name"
          header="Nombre"
          headerClassName="th-left"
          bodyClassName="td-left"
          sortable
        />
        <Column
          field="description"
          header="Descripción"
          headerClassName="th-left"
          bodyClassName="td-left"
          sortable
        />
        <Column
          field="price"
          header="Precio"
          headerClassName="th-right"
          bodyClassName="td-right"
          body={(row) =>
            row.price != null ? Number(row.price).toFixed(2) : ""
          }
          sortable
        />
        <Column
          field="stock"
          header="Stock"
          headerClassName="th-right"
          bodyClassName="td-right"
          sortable
        />
        <Column
          header="Acciones"
          body={(row: Product) => {
            const qtyInCart =
              items.find((p) => p.productId === row.id)?.quantity ?? 0;
            const outOfStock = Number(row.stock) <= 0;
            const reachedLimit = qtyInCart >= Number(row.stock);

            return (
              <a
                href="#"
                style={{
                  color: userRole === "ADMIN" || outOfStock || reachedLimit ? '#ccc' : '#007bff',
                  textDecoration: 'underline',
                  cursor: userRole === "ADMIN" || outOfStock || reachedLimit ? 'not-allowed' : 'pointer',
                  pointerEvents: userRole === "ADMIN" || outOfStock || reachedLimit ? 'none' : 'auto',
                  background: 'none',
                  border: 'none',
                  padding: 0,
                  font: 'inherit'
                }}
                onClick={e => {
                  e.preventDefault();
                  if (userRole === "ADMIN" || outOfStock || reachedLimit) return;
                  addToCart({
                    productId: row.id,
                    name: row.name,
                    unitPrice: Number(row.price),
                    stock: Number(row.stock),
                    quantity: 0
                  });
                  toast.current?.show({
                    severity: "info",
                    summary: "Carrito",
                    detail: `${row.name} agregado al carrito`,
                    life: 2000
                  });
                }}
              >Agregar al carrito</a>
            );
          }}
          headerClassName="th-right"
          bodyClassName="td-right"
        />
      </DataTable>

      <Dialog
        header="Crear Producto"
        visible={modalVisible}
        style={{ width: "800px" }}
        modal
        onHide={() => setModalVisible(false)}
      >
        <div className="modal-form">
          <div>
            <label>Nombre</label>
            <InputText
              value={formData.name || ""}
              onChange={(e) =>
                setFormData({ ...formData, name: e.target.value })
              }
            />
          </div>
          <div>
            <label>Descripción</label>
            <InputText
              value={formData.description || ""}
              onChange={(e) =>
                setFormData({ ...formData, description: e.target.value })
              }
            />
          </div>
          <div className="row-fields">
            <div>
              <label>Precio</label>
              <InputNumber
                value={formData.price ?? 0}
                onValueChange={(e) =>
                  setFormData({
                    ...formData,
                    price: e.value ?? undefined
                  })
                }
                mode="decimal"
                minFractionDigits={2}
                maxFractionDigits={2}
                min={0.01}
              />
            </div>
            <div>
              <label>Stock</label>
              <InputNumber
                value={formData.stock ?? 0}
                onValueChange={(e) =>
                  setFormData({
                    ...formData,
                    stock: e.value ?? undefined
                  })
                }
                mode="decimal"
                min={0}
              />
            </div>
          </div>
        </div>

        <Button
          label="Guardar"
          icon="pi pi-check"
          className="modal-save-button"
          onClick={saveProduct}
        />
      </Dialog>
    </div>
  );
}