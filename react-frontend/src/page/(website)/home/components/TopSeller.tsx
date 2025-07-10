import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Autoplay, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { notification } from "antd";
import axios from "axios";
import { Product, WishlistItem } from "@/types/product";

interface Order {
  id: number;
  status: string;
  cartItems: WishlistItem[];
}

const TopSellerPage = () => {
  const [topProducts, setTopProducts] = useState<Product[]>([]);
  const [productsMap, setProductsMap] = useState<{ [key: string]: Product }>(
    {}
  );

  // Lấy dữ liệu sản phẩm bán chạy và bảng sản phẩm
  useEffect(() => {
    const fetchData = async () => {
      try {
        // Lấy danh sách các sản phẩm từ bảng "products"
        const productsResponse = await axios.get(
          "http://localhost:3000/products"
        );
        const products = productsResponse.data;

        // Tạo một map để lưu trữ các sản phẩm theo tên, dễ dàng tra cứu sau này
        const productsMap: { [key: string]: Product } = {};
        products.forEach((product: Product) => {
          productsMap[product.name] = product;
        });

        setProductsMap(productsMap); // Lưu vào state productsMap

        // Lấy dữ liệu đơn hàng từ bảng "orders"
        const ordersResponse = await axios.get("http://localhost:3000/orders");
        const data = ordersResponse.data;

        // Lọc các đơn hàng đã giao thành công
        const successfulOrders = data.filter(
          (order: Order) => order.status === "Đã giao thành công"
        );

        // Tạo một đối tượng để theo dõi các sản phẩm bán
        const productSalesMap: {
          [key: string]: Product & { quantity: number };
        } = {};

        // Tính toán số lượng bán của từng sản phẩm, nhóm theo tên sản phẩm
        successfulOrders.forEach((order: Order) => {
          order.cartItems.forEach((product: WishlistItem) => {
            const productDetails = productsMap[product.name];

            if (productDetails) {
              // Nếu sản phẩm có trong bảng products
              if (productSalesMap[product.name]) {
                // Nếu sản phẩm đã có trong map (trùng tên), cộng dồn số lượng
                productSalesMap[product.name].quantity += product.quantity || 0;
              } else {
                // Nếu chưa có, thêm sản phẩm vào map (giữ id từ bảng products)
                productSalesMap[product.name] = {
                  ...productDetails,
                  quantity: product.quantity || 0,
                };
              }
            }
          });
        });

        // Chuyển dữ liệu từ đối tượng thành mảng
        const productSales = Object.values(productSalesMap);

        console.log(productSales); // Xem dữ liệu đã gộp chính xác chưa

        // Sắp xếp sản phẩm theo số lượng bán
        setTopProducts(
          productSales.sort((a, b) => b.quantity - a.quantity).slice(0, 5)
        );
      } catch (error) {
        console.error("Có lỗi khi lấy dữ liệu sản phẩm bán chạy:", error);
      }
    };

    fetchData();
  }, []);

  // Thêm sản phẩm vào giỏ hàng
  const addToCart = async (product: Product) => {
    try {
      const user = JSON.parse(localStorage.getItem("user") || "{}");

      if (user.id) {
        const response = await axios.get("http://localhost:3000/carts");
        const cart = response.data;
        const existingProduct = cart.find(
          (item: WishlistItem) => item.id === product.id
        );

        if (existingProduct) {
          existingProduct.quantity += 1;
          await axios.put(
            `http://localhost:3000/carts/${existingProduct.id}`,
            existingProduct
          );
          notification.success({
            message: "Sản phẩm đã được cập nhật",
            description: `Số lượng của ${product.name} đã được tăng lên.`,
          });
        } else {
          const newProduct = { ...product, quantity: 1, userId: user.id };
          await axios.post("http://localhost:3000/carts", newProduct);
          notification.success({
            message: "Thêm vào giỏ hàng thành công",
            description: `${product.name} đã được thêm vào giỏ hàng.`,
          });
        }
      } else {
        const localCart = JSON.parse(localStorage.getItem("cart") || "[]");
        const existingProduct = localCart.find(
          (item: WishlistItem) => item.id === product.id
        );

        if (existingProduct) {
          existingProduct.quantity += 1;
        } else {
          localCart.push({ ...product, quantity: 1 });
        }

        localStorage.setItem("cart", JSON.stringify(localCart));
        notification.success({
          message: "Thêm vào giỏ hàng thành công",
          description: `${product.name} đã được thêm vào giỏ hàng.`,
        });
      }
    } catch (error) {
      console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
      notification.error({
        message: "Lỗi",
        description: "Đã có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.",
      });
    }
  };
  // Hàm thêm sản phẩm vào wishlist
  const addToWishList = async (product: Product) => {
    try {
      const user = JSON.parse(localStorage.getItem("user") || "{}");

      if (!user.id) {
        // Nếu chưa đăng nhập, lưu vào localStorage
        const wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
        const existingProduct = wishlist.find(
          (item: WishlistItem) => item.productId === product.id
        );

        if (!existingProduct) {
          const newItem = {
            productId: product.id,
            name: product.name,
            price: product.price,
            imageUrl: product.imageUrl,
          };
          wishlist.push(newItem);
          localStorage.setItem("wishlist", JSON.stringify(wishlist));
          notification.success({
            message: "Thêm vào yêu thích thành công",
            description: `${product.name} đã được thêm vào danh sách yêu thích.`,
          });
        } else {
          notification.info({
            message: "Sản phẩm đã có trong danh sách yêu thích",
            description: `${product.name} đã có trong danh sách yêu thích của bạn.`,
          });
        }
      } else {
        // Nếu đã đăng nhập, gửi request lên server
        await axios.post("http://localhost:3000/wishlist", {
          userId: user.id,
          productId: product.id,
          name: product.name,
          price: product.price,
          imageUrl: product.imageUrl,
        });

        notification.success({
          message: "Thêm vào yêu thích thành công",
          description: `${product.name} đã được thêm vào danh sách yêu thích.`,
        });
      }
    } catch (error) {
      console.error("Lỗi khi thêm vào wishlist:", error);
      notification.error({
        message: "Lỗi",
        description:
          "Đã có lỗi xảy ra khi thêm sản phẩm vào danh sách yêu thích.",
      });
    }
  };
  return (
    <section className="max-w-7xl mx-auto p-4">
      <h2 className="text-[40px] text-center mb-8">Sản Phẩm Bán Chạy</h2>

      {/* Swiper Slider cho các sản phẩm bán chạy */}
      <Swiper
        modules={[Navigation, Autoplay, Pagination]}
        slidesPerView={1}
        spaceBetween={10}
        loop={true}
        autoplay={{ delay: 2500, disableOnInteraction: false }}
        navigation={true}
        pagination={{ clickable: true }}
        breakpoints={{
          640: { slidesPerView: 2 },
          768: { slidesPerView: 3 },
          1024: { slidesPerView: 4 },
        }}
        className="w-full max-w-7xl mx-auto"
      >
        {topProducts.map((product) => (
          <SwiperSlide key={product.id}>
            <div className="bg-[#F4F5F7]">
              {/* Box ảnh sản phẩm */}
              <div className="relative group h-80 overflow-hidden">
                <img
                  src={product.imageUrl}
                  alt={product.name}
                  className="w-full h-full object-cover transition duration-300 group-hover:opacity-70"
                />

                {/* Hiển thị thẻ Nổi bật */}
                <span className="absolute top-4 left-4 bg-yellow-500 text-white font-medium px-2 py-1 rounded-full">
                  Bán Chạy
                </span>

                {/* Các nút khi hover */}
                <div className="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 bg-black bg-opacity-50">
                  <button
                    onClick={() => addToCart(product)} // Thêm vào giỏ hàng
                    className="bg-white text-yellow-600 font-semibold py-3 px-11 mb-2"
                  >
                    Thêm vào giỏ hàng
                  </button>
                  <div className="flex space-x-4 text-white">
                    <button className="flex items-center space-x-1">
                      <i className="fa-solid fa-share-nodes" />
                      <span>Chia sẻ</span>
                    </button>
                    <button
                      className="flex items-center space-x-1"
                      title="So sánh"
                    >
                      <i className="fa-solid fa-arrow-right-arrow-left" />
                    </button>
                    <button
                      className="flex items-center space-x-1"
                      onClick={() => addToWishList(product)}
                    >
                      <i className="fas fa-heart" />
                      <span>Yêu Thích</span>
                    </button>
                  </div>
                </div>
              </div>

              {/* Box thông tin sản phẩm */}
              <div className="mt-3 bg-[#F4F5F7] pt-4 pl-4 pb-8">
                <h3 className="font-semibold text-2xl mb-2">
                  <Link
                    to={`/shop/${product.id}`} // Link đến sản phẩm chi tiết
                    className="hover:text-yellow-600"
                  >
                    {product.name.length > 20
                      ? `${product.name.substring(0, 20)}...`
                      : product.name}
                  </Link>
                </h3>
                <div className="text-[#3a3a3a] font-semibold">
                  <span className="mr-3">
                    {product.price.toLocaleString()}
                    <sup>đ</sup>
                  </span>
                </div>
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default TopSellerPage;
