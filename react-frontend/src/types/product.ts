export interface Product {
  id: number;
  name: string;
  price: number;
  imageUrl: string;
  categoryName: string;
  noibat?: boolean;
  createdAt: string;
}

export interface WishlistItem {
  id: number;
  userId?: number;
  productId: number;
  name: string;
  price: number;
  imageUrl: string;
}
