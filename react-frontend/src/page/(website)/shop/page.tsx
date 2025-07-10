import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import axios from "axios";
import { notification, Pagination } from "antd";

interface Product {
  id: number;
  name: string;
  price: number;
  imageUrl: string;
  categoryName: string;
  noibat?: boolean;
  createdAt: string;
}

interface Category {
  id: number;
  name: string;
}

const ShopPage = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [priceFilter, setPriceFilter] = useState("all");
  const [currentPage, setCurrentPage] = useState(1);
  const [pageSize, setPageSize] = useState(8);
  const [categories, setCategories] = useState<Category[]>([]);
  const [selectedCategory, setSelectedCategory] = useState("all");
  const nav = useNavigate();

  useEffect(() => {
    // Lấy danh sách sản phẩm
    fetch("http://localhost:3000/products")
      .then((response) => response.json())
      .then((data) => {
        setProducts(data);
        setFilteredProducts(data);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy sản phẩm:", error);
      });

    // Lấy danh sách danh mục
    fetch("http://localhost:3000/categories")
      .then((response) => response.json())
      .then((data) => {
        setCategories(data);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy danh mục:", error);
      });
  }, []);

  // Hàm tìm kiếm sản phẩm theo tên
  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value.toLowerCase();
    setSearchTerm(value);
    filterProducts(value, selectedCategory, priceFilter);
  };

  // Hàm lọc sản phẩm theo tên, danh mục và giá
  const filterProducts = (search: string, category: string, price: string) => {
    let filtered = products.filter((product) =>
      product.name.toLowerCase().includes(search)
    );

    // Lọc theo danh mục
    if (category !== "all") {
      filtered = filtered.filter(
        (product) => product.categoryName === category
      );
    }

    // Lọc theo giá
    if (price === "newest") {
      filtered = filtered.sort(
        (a, b) =>
          new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
      );
    } else if (price === "lowToHigh") {
      filtered = filtered.sort((a, b) => a.price - b.price);
    } else if (price === "highToLow") {
      filtered = filtered.sort((a, b) => b.price - a.price);
    }

    setFilteredProducts(filtered);
  };

  // Hàm lọc theo danh mục
  const handleCategoryFilter = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const category = e.target.value;
    setSelectedCategory(category);
    filterProducts(searchTerm, category, priceFilter);
  };

  // Hàm lọc theo giá
  const handlePriceFilter = (value: string) => {
    setPriceFilter(value);
    filterProducts(searchTerm, selectedCategory, value);
  };

  // Hàm thêm sản phẩm vào giỏ hàng
  const addToCart = async (product: Product) => {
    // Kiểm tra xem người dùng đã đăng nhập chưa
    const user = JSON.parse(localStorage.getItem("user") || "{}");

    // Nếu người dùng chưa đăng nhập
    if (!user.id) {
      // Kiểm tra xem sản phẩm đã có trong giỏ hàng trong localStorage chưa
      const cart = JSON.parse(localStorage.getItem("cart") || "[]");

      const existingProduct = cart.find(
        (item: any) => item.productId === product.id
      );

      if (existingProduct) {
        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        existingProduct.quantity += 1;
      } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới vào giỏ hàng
        const newProduct = {
          productId: product.id,
          name: product.name,
          price: product.price,
          imageUrl: product.imageUrl,
          quantity: 1,
        };
        cart.push(newProduct);
      }

      // Lưu giỏ hàng vào localStorage
      localStorage.setItem("cart", JSON.stringify(cart));

      notification.success({
        message: "Thêm vào giỏ hàng thành công",
        description: `${product.name} đã được thêm vào giỏ hàng.`,
      });
    } else {
      // Nếu người dùng đã đăng nhập
      try {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng của người dùng chưa
        const response = await axios.get("http://localhost:3000/carts");
        const cart = response.data;
        const existingProduct = cart.find(
          (item: any) =>
            item.productId === product.id && item.userId === user.id
        );

        if (existingProduct) {
          // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
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
          // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới vào giỏ hàng
          const newProduct = {
            userId: user.id,
            productId: product.id,
            name: product.name,
            price: product.price,
            imageUrl: product.imageUrl,
            quantity: 1,
          };
          await axios.post("http://localhost:3000/carts", newProduct);
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
    }
  };

  // Hàm thêm sản phẩm vào wishlist
  const addToWishLish = async (product: Product) => {
    try {
      const user = JSON.parse(localStorage.getItem("user") || "{}");

      if (!user.id) {
        // Nếu chưa đăng nhập, lưu vào localStorage
        const wishlist = JSON.parse(localStorage.getItem("wishlist") || "[]");
        const existingProduct = wishlist.find(
          (item: any) => item.productId === product.id
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
        const response = await axios.post("http://localhost:3000/wishlist", {
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

  // Hàm phân trang
  const handlePageChange = (page: number, pageSize: number) => {
    setCurrentPage(page);
    setPageSize(pageSize);
  };

  const startIndex = (currentPage - 1) * pageSize;
  const paginatedProducts = filteredProducts.slice(
    startIndex,
    startIndex + pageSize
  );

  return (
    <>
      <div className="max-w-7xl mx-auto p-6">
        {/* Breadcrumb */}
        <div className="flex items-center space-x-4 text-sm text-gray-700 mb-6">
          <span className="text-gray-500 hover:text-gray-800 cursor-pointer transition duration-300">
            <Link to="/">Trang chủ</Link>
          </span>
          <span className="text-gray-400">/</span>
          <span className="text-gray-800 font-medium">Sản phẩm</span>
        </div>
      </div>

      <section className="max-w-7xl mx-auto p-4">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
          <input
            type="text"
            placeholder="Tìm kiếm sản phẩm..."
            value={searchTerm}
            onChange={handleSearch}
            className="w-full md:w-1/3 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
          />
          <div className="flex gap-4 w-full md:w-auto">
            <select
              value={priceFilter}
              onChange={(e) => handlePriceFilter(e.target.value)}
              className="w-1/2 md:w-auto p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
              title="Lọc theo giá"
            >
              <option value="all">Tất cả sản phẩm</option>
              <option value="newest">Sản phẩm mới nhất</option>
              <option value="lowToHigh">Giá: Thấp đến Cao</option>
              <option value="highToLow">Giá: Cao đến Thấp</option>
            </select>

            <select
              value={selectedCategory}
              onChange={handleCategoryFilter}
              className="w-1/2 md:w-auto p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
              title="Lọc theo danh mục"
            >
              <option value="all">Tất cả danh mục</option>
              {categories.map((category) => (
                <option key={category.id} value={category.name}>
                  {category.name}
                </option>
              ))}
            </select>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {paginatedProducts.map((product) => (
            <div
              key={product.id}
              className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
            >
              {/* Box ảnh sản phẩm */}
              <div className="relative group h-80 overflow-hidden">
                <img
                  src={product.imageUrl}
                  alt={product.name}
                  className="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                />

                {/* Hiển thị sản phẩm nổi bật */}
                {product.noibat && (
                  <span className="absolute top-4 left-4 bg-yellow-500 text-white font-medium px-3 py-1 rounded-full text-sm">
                    Nổi bật
                  </span>
                )}

                {/* Các nút khi hover */}
                <div className="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 bg-black bg-opacity-50">
                  <button
                    onClick={() => addToCart(product)}
                    className="bg-white text-yellow-600 font-semibold py-3 px-8 mb-2 rounded-lg hover:bg-yellow-600 hover:text-white transition-colors duration-300"
                  >
                    Thêm vào giỏ hàng
                  </button>
                  <div className="flex space-x-4 text-white">
                    <button className="flex items-center space-x-1 hover:text-yellow-400 transition-colors duration-300">
                      <i className="fa-solid fa-share-nodes" />
                      <span>Chia sẻ</span>
                    </button>
                    <button
                      className="flex items-center space-x-1 hover:text-yellow-400 transition-colors duration-300"
                      title="So sánh sản phẩm"
                    >
                      <i className="fa-solid fa-arrow-right-arrow-left" />
                    </button>
                    <button
                      className="flex items-center space-x-1 hover:text-yellow-400 transition-colors duration-300"
                      onClick={() => addToWishLish(product)}
                    >
                      <i className="fas fa-heart" />
                      <span>Yêu thích</span>
                    </button>
                  </div>
                </div>
              </div>

              {/* Box thông tin sản phẩm */}
              <div className="p-4">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-sm text-gray-500">
                    {product.categoryName}
                  </span>
                  <div className="flex items-center text-yellow-500">
                    <i className="fas fa-star" />
                    <i className="fas fa-star" />
                    <i className="fas fa-star" />
                    <i className="fas fa-star" />
                    <i className="fas fa-star-half-alt" />
                  </div>
                </div>

                <h3 className="font-semibold text-lg mb-2">
                  <Link
                    to={`/shop/${product.id}`}
                    className="hover:text-yellow-600 transition-colors duration-300"
                  >
                    {product.name}
                  </Link>
                </h3>

                <div className="flex items-center justify-between">
                  <div className="text-[#3a3a3a] font-bold text-lg">
                    <span>
                      {product.price.toLocaleString()}
                      <sup>đ</sup>
                    </span>
                  </div>
                  <div className="text-sm text-gray-500">
                    <i className="fas fa-shopping-cart mr-1" />
                    <span>Đã bán: 100+</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Phân trang */}
        <div className="flex justify-center mt-10">
          <Pagination
            current={currentPage}
            pageSize={pageSize}
            total={filteredProducts.length}
            onChange={handlePageChange}
            className="ant-pagination-item-active:bg-yellow-500 ant-pagination-item-active:border-yellow-500"
          />
        </div>
      </section>
    </>
  );
};

export default ShopPage;
