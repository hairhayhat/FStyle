import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import {
  Form,
  Input,
  Button,
  message,
  Upload,
  Avatar,
  Space,
  Modal,
} from "antd";
import { UserOutlined, UploadOutlined } from "@ant-design/icons";
import { User } from "D:/FW2_Spring2025-team/src/types/user.ts";

import type { UploadChangeParam } from "antd/es/upload";
import type { UploadFile } from "antd/es/upload/interface";
const ProfilePage = () => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(false);
  const [showAvatarInput, setShowAvatarInput] = useState(false);
  const [avatarUrl, setAvatarUrl] = useState("");
  const [showPasswordForm, setShowPasswordForm] = useState(false);
  const [passwordForm] = Form.useForm();
  const [form] = Form.useForm();
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUserData = async () => {
      try {
        const userData = JSON.parse(localStorage.getItem("user") || "{}");
        if (!userData.id) {
          message.error("Vui lòng đăng nhập để xem thông tin cá nhân");
          navigate("/login");
          return;
        }

        const response = await axios.get(
          `http://localhost:3000/users/${userData.id}`
        );
        setUser(response.data);
        form.setFieldsValue(response.data);
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        message.error("Không thể lấy thông tin người dùng");
      }
    };

    fetchUserData();
  }, [form, navigate]);

  const handleUpdate = async (values: Partial<User>) => {
    if (!user) return;

    try {
      setLoading(true);
      const updatedUser = { ...user, ...values };
      await axios.put(`http://localhost:3000/users/${user.id}`, updatedUser);
      message.success("Cập nhật thông tin thành công");

      localStorage.setItem("user", JSON.stringify(updatedUser));
      setUser(updatedUser);
    } catch (error) {
      console.error("Lỗi khi cập nhật thông tin:", error);
      message.error("Cập nhật thông tin thất bại");
    } finally {
      setLoading(false);
    }
  };

  const handleAvatarSubmit = async () => {
    if (!user || !avatarUrl) return;

    try {
      setLoading(true);
      const updatedUser = { ...user, avatar: avatarUrl };
      await axios.put(`http://localhost:3000/users/${user.id}`, updatedUser);

      setUser(updatedUser);
      localStorage.setItem("user", JSON.stringify(updatedUser));
      message.success("Cập nhật ảnh đại diện thành công");
      setShowAvatarInput(false);
      setAvatarUrl("");
    } catch (error) {
      console.error("Lỗi khi cập nhật ảnh đại diện:", error);
      message.error("Cập nhật ảnh đại diện thất bại");
    } finally {
      setLoading(false);
    }
  };

  const handlePasswordChange = async (values: {
    oldPassword: string;
    newPassword: string;
    confirmPassword: string;
  }) => {
    if (!user) return;

    if (values.newPassword !== values.confirmPassword) {
      message.error("Mật khẩu mới không khớp!");
      return;
    }

    try {
      setLoading(true);

      // Lấy thông tin user hiện tại
      const currentUser = await axios.get(
        `http://localhost:3000/users/${user.id}`
      );

      // Kiểm tra mật khẩu cũ
      if (currentUser.data.password !== values.oldPassword) {
        message.error("Mật khẩu cũ không đúng!");
        return;
      }

      // Cập nhật mật khẩu mới
      const updatedUser = { ...currentUser.data, password: values.newPassword };
      await axios.put(`http://localhost:3000/users/${user.id}`, updatedUser);

      message.success("Đổi mật khẩu thành công!");
      setShowPasswordForm(false);
      passwordForm.resetFields();
    } catch (error) {
      console.error("Lỗi khi đổi mật khẩu:", error);
      message.error("Đổi mật khẩu thất bại!");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-4xl mx-auto p-6">
      <div className="bg-white rounded-lg shadow-md p-8">
        <h1 className="text-2xl font-semibold mb-6">Thông tin cá nhân</h1>

        <div className="flex flex-col md:flex-row gap-8">
          {/* Phần ảnh đại diện */}
          <div className="flex flex-col items-center">
            <Avatar
              size={150}
              src={user?.avatar}
              icon={<UserOutlined />}
              className="mb-4"
            />
            <Space direction="vertical" size="middle">
              <Button
                icon={<UploadOutlined />}
                onClick={() => setShowAvatarInput(true)}
              >
                Thay đổi ảnh đại diện
              </Button>

              {showAvatarInput && (
                <Space direction="vertical" size="small">
                  <Input
                    placeholder="Nhập URL ảnh đại diện"
                    value={avatarUrl}
                    onChange={(e) => setAvatarUrl(e.target.value)}
                    className="w-64"
                  />
                  <Space>
                    <Button
                      type="primary"
                      onClick={handleAvatarSubmit}
                      loading={loading}
                    >
                      Cập nhật
                    </Button>
                    <Button
                      onClick={() => {
                        setShowAvatarInput(false);
                        setAvatarUrl("");
                      }}
                    >
                      Hủy
                    </Button>
                  </Space>
                </Space>
              )}
            </Space>
          </div>

          {/* Form thông tin */}
          <div className="flex-1">
            <Form
              form={form}
              layout="vertical"
              onFinish={handleUpdate}
              initialValues={user || undefined}
            >
              <Form.Item
                name="email"
                label="Email"
                rules={[
                  { required: true, message: "Vui lòng nhập email" },
                  { type: "email", message: "Email không hợp lệ" },
                ]}
              >
                <Input />
              </Form.Item>

              <Form.Item label="Mật Khẩu">
                <Button onClick={() => setShowPasswordForm(true)}>
                  Đổi mật khẩu
                </Button>
              </Form.Item>

              <Form.Item
                name="fullName"
                label="Họ và tên"
                rules={[{ required: true, message: "Vui lòng nhập họ và tên" }]}
              >
                <Input />
              </Form.Item>

              <Form.Item
                name="phone"
                label="Số điện thoại"
                rules={[
                  { required: true, message: "Vui lòng nhập số điện thoại" },
                  {
                    pattern: /^[0-9]{10,11}$/,
                    message: "Số điện thoại không hợp lệ",
                  },
                ]}
              >
                <Input />
              </Form.Item>

              <Form.Item
                name="address"
                label="Địa chỉ"
                rules={[{ required: true, message: "Vui lòng nhập địa chỉ" }]}
              >
                <Input.TextArea rows={3} />
              </Form.Item>

              <Form.Item>
                <Button
                  type="primary"
                  htmlType="submit"
                  loading={loading}
                  className="bg-[#B88E2F] hover:bg-[#A77D2A]"
                >
                  Cập nhật thông tin
                </Button>
              </Form.Item>
            </Form>
          </div>
        </div>
      </div>

      <Modal
        title="Đổi mật khẩu"
        open={showPasswordForm}
        onCancel={() => {
          setShowPasswordForm(false);
          passwordForm.resetFields();
        }}
        footer={null}
      >
        <Form
          form={passwordForm}
          layout="vertical"
          onFinish={handlePasswordChange}
        >
          <Form.Item
            name="oldPassword"
            label="Mật khẩu cũ"
            rules={[{ required: true, message: "Vui lòng nhập mật khẩu cũ" }]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item
            name="newPassword"
            label="Mật khẩu mới"
            rules={[
              { required: true, message: "Vui lòng nhập mật khẩu mới" },
              { min: 6, message: "Mật khẩu phải có ít nhất 6 ký tự" },
            ]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item
            name="confirmPassword"
            label="Xác nhận mật khẩu mới"
            rules={[
              { required: true, message: "Vui lòng xác nhận mật khẩu mới" },
              ({ getFieldValue }) => ({
                validator(_, value) {
                  if (!value || getFieldValue("newPassword") === value) {
                    return Promise.resolve();
                  }
                  return Promise.reject(new Error("Mật khẩu không khớp!"));
                },
              }),
            ]}
          >
            <Input.Password />
          </Form.Item>

          <Form.Item>
            <Button
              type="primary"
              htmlType="submit"
              loading={loading}
              className="bg-[#B88E2F] hover:bg-[#A77D2A]"
            >
              Xác nhận
            </Button>
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
};

export default ProfilePage;
