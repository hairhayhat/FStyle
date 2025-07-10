import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import axios from "axios";
import { notification, message } from "antd";
import { AiFillDelete } from "react-icons/ai";
import Title from "antd/es/skeleton/Title";

interface WishlistItem {
  id: number;
  userId?: number;
  productId: number;
  name: string;
  price: number;
  imageUrl: string;
  categoryName?: string;
}

const WishList = () => {
  const [wishlist, setWishlist] = useState<WishlistItem[]>([]);
  const [isLoggedIn, setIsLoggedIn] = useState<boolean>(false);
  const nav = useNavigate();

  useEffect(() => {
    const user = JSON.parse(localStorage.getItem("user") || "{}");
    if (user.id) {
      setIsLoggedIn(true);
      fetchWishlist();
    } else {
      const storedWishlist = localStorage.getItem("wishlist");
      if (storedWishlist) {
        setWishlist(JSON.parse(storedWishlist));
      }
    }
  }, []);

  const fetchWishlist = async () => {
    try {
      const response = await axios.get("http://localhost:3000/wishlist");
      setWishlist(response.data);
    } catch (error) {
      console.error("Lỗi khi lấy wishlist:", error);
      message.error("Không thể lấy dữ liệu từ server!");
    }
  };

  const removeItem = async (id: number) => {
    if (isLoggedIn) {
      try {
        await axios.delete(`http://localhost:3000/wishlist/${id}`);
        setWishlist(wishlist.filter((item) => item.id !== id));
        message.success("Đã xóa sản phẩm khỏi danh sách yêu thích!");
      } catch (error) {
        console.error("Lỗi khi xóa sản phẩm:", error);
        message.error("Xóa sản phẩm thất bại!");
      }
    } else {
      const updatedWishlist = wishlist.filter((item) => item.id !== id);
      setWishlist(updatedWishlist);
      localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
      message.success("Đã xóa sản phẩm khỏi danh sách yêu thích!");
    }
  };

  const addToCart = async (item: WishlistItem) => {
    try {
      const user = JSON.parse(localStorage.getItem("user") || "{}");

      if (user.id) {
        await axios.post("http://localhost:3000/carts", {
          userId: user.id,
          productId: item.productId,
          name: item.name,
          price: item.price,
          imageUrl: item.imageUrl,
          quantity: 1,
        });
        message.success("Đã thêm sản phẩm vào giỏ hàng!");
      } else {
        const cart = JSON.parse(localStorage.getItem("cart") || "[]");
        const existingProduct = cart.find(
          (product: any) => product.productId === item.productId
        );

        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          cart.push({
            productId: item.productId,
            name: item.name,
            price: item.price,
            imageUrl: item.imageUrl,
            quantity: 1,
          });
        }

        localStorage.setItem("cart", JSON.stringify(cart));
        message.success("Đã thêm sản phẩm vào giỏ hàng!");
      }
    } catch (error) {
      console.error("Lỗi khi thêm vào giỏ hàng:", error);
      message.error("Thêm vào giỏ hàng thất bại!");
    }
  };

  return (
    <div className="w-[1280px] mx-auto flex mt-12 mb-16">
      {wishlist.length === 0 ? (
        <div className="flex flex-col items-center justify-center w-full h-[400px]">
          <h2 className="text-2xl font-semibold">Danh sách yêu thích trống</h2>
          <p className="text-gray-500">
            Hiện tại danh sách yêu thích của bạn chưa có sản phẩm nào.
          </p>
          <button
            onClick={() => nav("/shop")}
            className="mt-4 px-6 py-2 bg-[#B88E2F] text-white rounded-lg hover:bg-[#A77D2A] transition-colors duration-300"
          >
            Mua sắm ngay
          </button>
        </div>
      ) : (
        <div className="w-full">
          <h1 className="text-2xl font-semibold mb-4 text-center">
            Danh sách yêu thích
          </h1>
          <table className="w-full text-[#262626] text-base font-medium font-poppins table-auto">
            <thead className="bg-[#F9F1E7] text-left">
              <tr>
                <th className="py-4 pl-24">Sản phẩm</th>
                <th className="py-4">Giá</th>
                <th className="py-4">Danh mục</th>
                <th className="py-4"></th>
                <th className="py-4"></th>
              </tr>
            </thead>
            <tbody className="text-base font-medium text-[#A3A3A3]">
              {wishlist.map((item) => (
                <tr key={item.id} className="border-b border-gray-200">
                  <td className="py-[55px] flex items-center space-x-4">
                    <img
                      src={item.imageUrl}
                      alt={item.name}
                      className="w-[80px] h-[80px] rounded-md bg-[#F9F1E7] object-cover"
                    />
                    <span className="hover:text-[#B88E2F] transition-colors duration-300">
                      <Link to={`/shop/${item.productId}`}>{item.name}</Link>
                    </span>
                  </td>
                  <td className="text-left">{item.price.toLocaleString()}đ</td>
                  <td>{item.categoryName || "Không có danh mục"}</td>
                  <td>
                    <button
                      onClick={() => addToCart(item)}
                      className="px-4 py-2 bg-[#B88E2F] text-white rounded-lg hover:bg-[#A77D2A] transition-colors duration-300"
                    >
                      Thêm vào giỏ hàng
                    </button>
                  </td>
                  <td>
                    <AiFillDelete
                      className="text-2xl text-[#B88E2F] cursor-pointer hover:text-[#A77D2A] transition-colors duration-300"
                      onClick={() => removeItem(item.id)}
                    />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default WishList;
