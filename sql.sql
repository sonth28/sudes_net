-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 21, 2024 lúc 11:21 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Cơ sở dữ liệu: `sudes_net`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
                         `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `order_id` int(11) NOT NULL,
                         `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
                          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                          `user_id` int(11) NOT NULL,
                          `product_id` int(11) NOT NULL,
                          `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
                            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `vendor_id` int(11) NOT NULL,
                            `name` text NOT NULL,
                            `quantity` int(11) NOT NULL DEFAULT 0,
                            `image` text DEFAULT NULL,
                            `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `name` varchar(50) NOT NULL,
                         `email` varchar(50) NOT NULL,
                         `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `venders`
--

CREATE TABLE `venders` (
                           `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                           `name` varchar(50) NOT NULL DEFAULT '',
                           `age` varchar(50) NOT NULL DEFAULT '0',
                           `phone_number` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


