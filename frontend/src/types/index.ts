export interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  stock: number;
}

export interface Order {
  id: number;
  customerId: string;
  items: { productId: number; quantity: number }[];
  total: number | string;
}
