-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 21, 2024 lúc 05:48 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cent_beauty`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date_slot` int(11) DEFAULT NULL,
  `time_id` int(11) DEFAULT NULL,
  `patient_name` varchar(50) DEFAULT NULL,
  `patient_gender` tinyint(1) DEFAULT NULL,
  `patient_dob` date DEFAULT NULL,
  `patient_phone` varchar(11) DEFAULT NULL,
  `patient_email` varchar(150) DEFAULT NULL,
  `patient_description` varchar(500) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `result` varchar(500) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `service_id`, `employee_id`, `date_slot`, `time_id`, `patient_name`, `patient_gender`, `patient_dob`, `patient_phone`, `patient_email`, `patient_description`, `status`, `result`, `update_at`, `update_by`, `created_at`) VALUES
(100, NULL, 1, 104, 19896, 15, 'Hoa Minh', 0, '1997-04-02', '0987654322', 'hoaminh678@gmail.com', 'Đổ mồ hôi đêm', 2, NULL, '2024-06-22 05:53:23', 67, '2024-06-20 01:14:38'),
(101, NULL, 3, 105, 19896, 6, 'Nguyễn Thùy Linh', 0, '1968-10-20', '0966444453', 'nguyenthuylinh852003@gmail.com', 'Đau bụng', 1, NULL, '2024-06-22 05:52:02', 67, '2024-06-20 01:15:56'),
(102, 1, 1, 105, 19897, 10, 'Vũ Thị Thắm', 1, '2002-05-23', '0987654321', 'tham@gmail.com', 'Đau nửa đầu, buồn nôn', 3, NULL, '2024-06-22 06:41:37', 76, '2024-06-22 06:40:43'),
(103, NULL, 24, 103, 20020, 4, 'Bệnh nhân 36', 1, '2002-10-30', '0964444444', 'chatgpt3010@gmail.com', 'không biết gì cả', 2, NULL, NULL, NULL, '2024-10-23 00:05:03'),
(104, NULL, 1, 107, 20048, 1, 'Khách hàng vãng lai 1', 1, '2002-10-30', '0978901234', 'kh1@gmail.com', 'không', 2, NULL, NULL, NULL, '2024-11-20 23:42:16'),
(105, 53, 1, 107, 20048, 11, 'Trần Huyền Trang', 0, '2002-10-30', '0888888888', 'trang01@gmail.com', '', 2, NULL, NULL, NULL, '2024-11-21 01:17:34'),
(106, NULL, 1, 107, 20050, 9, 'Vũ Đức Thành', 1, '2002-02-20', '0966444453', 'chatgpt3010@gmail.com', 'đẹp trai', 2, NULL, NULL, NULL, '2024-11-22 00:24:57'),
(107, NULL, 1, 107, 20050, 9, 'Vũ Đức Thành', 1, '2002-02-20', '0966444453', 'chatgpt3010@gmail.com', 'đẹp trai', 2, NULL, '2024-12-09 23:59:34', 1, '2024-11-22 00:24:59'),
(108, NULL, 3, 105, 20049, 11, 'Vũ Thuý Hiền', 0, '2002-03-02', '0978901234', 'tanh09644@gmail.com', 'xinh gái', 2, NULL, NULL, NULL, '2024-11-22 00:31:33'),
(109, NULL, 1, 107, 20067, 5, 'Vũ Minh Ánh', 1, '2002-10-30', '09060606060', 'tanh09644@gmail.com', 'xam moi', 3, NULL, '2024-12-20 00:18:37', 1, '2024-12-09 20:58:59'),
(110, NULL, 1, 103, 20067, 3, 'Vũ Tuấn Anh', 1, '2002-10-30', '0964434877', 'tanh09644@gmail.com', 'aaaa', 2, NULL, '2024-12-09 23:49:04', 1, '2024-12-09 23:36:24'),
(111, NULL, 2, 103, 20068, 5, 'Phạm Hoàng Bách', 1, '2003-11-20', '0966444453', 'chatgpt3010@gmail.com', 'Tui đưa bạn gái đi ', 2, NULL, '2024-12-10 21:53:28', 108, '2024-12-10 21:52:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`patient_id`, `name`, `password`, `dob`, `gender`, `address`, `phone`, `email`, `status`, `create_at`, `update_by`, `update_at`) VALUES
(53, 'Vũ Ngọc Huyền', '$2a$12$RIhSPN36VEq3iASaeUrzfODmeM7FL4.eXLAdYd2DPSZIEx4ZhA/3a', '2002-10-30', 0, '11 Kim Mã Thượng, Ba Đình, Hà Nội', '0888888888', 'trang01@gmail.com', 1, NULL, NULL, NULL),
(54, 'Khách hàng 1', '$2y$12$PCaalATjCIy5jUwM.jCcGe/jWA.lazJFzLI1myYeqnaUSFd5/3mmW', NULL, NULL, NULL, '0777777777', NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `employee_code` varchar(55) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avt` varchar(250) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`employee_id`, `role_id`, `service_id`, `position_id`, `employee_code`, `name`, `password`, `avt`, `phone`, `email`, `dob`, `gender`, `address`, `status`, `create_at`, `update_at`, `update_by`) VALUES
(1, 1, NULL, NULL, 'ADMIN', 'Vũ Ngọc Huyền', '$2a$12$EVhqIYDVLnnhtvWVQisDZuGgvcOUNC9/tRteBZ3d7UnFc4NJfZEk6', 'https://cdn.vectorstock.com/i/500p/52/38/avatar-icon-vector-11835238.jpg', '0999999999', 'admin@gmail.com', '2002-12-01', 1, '20 Hồ Tùng Mậu, Mai Dịch, Cầu Giấy, Hà Nội', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(103, 2, 2, 1, 'DOC103', 'Vũ Ngọc Huyền', '$2a$12$EVhqIYDVLnnhtvWVQisDZuGgvcOUNC9/tRteBZ3d7UnFc4NJfZEk6', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1733553495/cb75axukdbpxlzg9zvdp.jpg', '0999999998', 'huyen123@gmail.com', '2002-10-30', 0, '8 Đồng Bát, Mỹ Đình 2, Nam Từ Liêm, Hà Nội', 1, '2024-11-20 22:12:58', '2024-12-07 13:38:16', 1),
(104, 2, 3, 1, 'DOC104', 'Vũ Tuấn Anhh', '$2y$12$w2GUWLjPGl.W.zscOaCkIuavZ6Sc6qLVrZgokbESTGt/2MT.Oi.xq', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1733553612/x1o3ncarousuy05ksdgl.jpg', '0999999997', 'anhvt123@gmail.com', '2002-10-30', 1, '858 Kim Giang, Thanh Liệt, Thanh Trì, Hà Nội', 1, '2024-11-20 22:27:16', '2024-12-07 13:40:13', 1),
(105, 2, 3, 4, 'EMP105', 'Nguyễn Thị Thu Huyền', '$2y$12$x.GG5skVdgx4yjJjy0UR/eqHw8A8pFK67HxX9lJTQew9YbRqvCp7a', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1733553560/do5xwbdocyxy8pmyu3gk.jpg', '0999999996', 'thuhuyen11@gmail.com', '2002-05-22', 0, '217 Lạc Long Quân, Tây Hồ , Hà Nội', 1, '2024-11-20 16:50:58', '2024-12-07 13:39:21', 1),
(106, 2, 4, 2, 'EMP106', 'Vũ Thị Thuý Hiền', '$2y$12$GfT9gFjwhGOzkupG23LJh.qyH38BEx1ZbKHnqx1E7c6djhf1eDVHO', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1732118327/wyzp3iqeqwm08grpudzs.jpg', '0999999995', 'thuyhien22@gmail.com', '2002-10-30', 0, 'Tân Hồng, Bình Giang, Hải Dương', 1, '2024-11-20 16:58:47', NULL, 1),
(107, 2, 1, 5, 'EMP107', 'Vũ Minh Anh', '$2y$12$ZMlb8gXm4Rc8eA1G9RWUTunTlDU39jmcAayIgFbnvJSDRUsNOOwCq', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1732120230/crf4ow00e8jvi1v29m3d.jpg', '0988888887', 'anh11@gmail.com', '2002-06-10', 1, 'Vĩnh Hồng, Bình Giang, Hải Dương', 0, '2024-11-20 23:30:30', '2024-12-09 21:05:00', 1),
(108, 1, 1, 1, 'ADMIN2', 'Thành Hưng', '$2a$12$EVhqIYDVLnnhtvWVQisDZuGgvcOUNC9/tRteBZ3d7UnFc4NJfZEk6', 'https://res.cloudinary.com/dzuahpxqv/image/upload/v1733841237/uwkf19fqhrgjc05sr0jv.jpg', '0999999994', 'thanhhung@gmail.com', '2003-04-28', 1, 'Hà Nội', 1, NULL, NULL, NULL),
(109, 1, 1, 1, 'ADMIN3', 'Phạm Hoàng Khiêm', '$2a$12$EVhqIYDVLnnhtvWVQisDZuGgvcOUNC9/tRteBZ3d7UnFc4NJfZEk6', 'https://res.cloudinary.com/dzuahpxqv/image/upload/v1733841237/uwkf19fqhrgjc05sr0jv.jpg', '0999999993', 'hoangkhiem@gmail.com', '2003-04-28', 1, 'Hà Nội', 1, NULL, NULL, NULL),
(110, 1, 1, 1, 'ADMIN4', 'Nguyễn Thị Thu Huyyền', '$2a$12$EVhqIYDVLnnhtvWVQisDZuGgvcOUNC9/tRteBZ3d7UnFc4NJfZEk6', 'https://res.cloudinary.com/dzuahpxqv/image/upload/v1733841237/uwkf19fqhrgjc05sr0jv.jpg', '0999999992', 'thuhuyen@gmail.com', '2003-04-28', 0, 'Hà Nội', 1, NULL, NULL, NULL),
(111, 2, 1, 5, 'EMP111', 'Vũ Văn Khang', '$2y$12$yW8KkoHlCQQ.hNEBMnQt7.iD/kLwBtXGmvPX/O0.IxcZCetvTirwS', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1734371711/vygl8ceecprfun9raoul.jpg', '0988526666', 'khang02@gmail.com', '2002-06-10', 1, '266 Đội Cấn, Ba Đình, Hà Nội', 1, '2024-12-17 00:55:13', NULL, 1),
(112, 3, 1, 5, 'EMP112', 'Khuất Bá Tiến', '$2y$12$ovHUfZhvRgq08xQADQW1IuiZJdVrZVN/DQGoRlk0Nktnmt1DvPjYq', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1734372489/h2eurmixpjg35seprjxb.jpg', '0964434876', 'tien97@gmail.com', '1997-04-30', 1, '66 Hồ Tùng Mậu, Mai Dịch, Cầu Giấy, Hà Nội', 1, '2024-12-17 01:08:11', NULL, 1),
(113, 2, 38, 5, 'EMP113', 'Chuyên Viên', '$2y$12$mV7tE0cHbvYorP2CfsidtucI/Z9KDKVnPifripdYovSteeDe0MNm.', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1734623110/tzawbf4c759ufrsnvjg7.jpg', '0999999111', 'chuyenvien@gmail.com', '2003-01-30', 1, '281 Đội Cấn, Ba Đình, Hà Nội', 1, '2024-12-19 22:45:12', NULL, 1),
(114, 3, 1, 2, 'EMP114', 'Nhân Viên Tư Vấn', '$2y$12$X3fVY1i16UDOuSM4pZPPUuQqr8HN25QfgK7DRqUCt7HPcpfvsDwCy', 'https://res.cloudinary.com/dnp6p86dp/image/upload/v1734629486/lcx2brywwsi4n5pwynew.jpg', '0999999222', 'tuvan@gmail.com', '2003-06-12', 1, '8 Đồng Bát, Mỹ Đình, Hà Nội', 1, '2024-12-20 00:31:27', NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `positions`
--

INSERT INTO `positions` (`position_id`, `name`) VALUES
(1, 'Giám sát viên'),
(2, 'Thợ chính'),
(3, 'Thợ phụ'),
(4, 'Học việc'),
(5, 'Giám sát viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'admin', 'Quản trị viên'),
(2, 'employee', 'Chuyên viên'),
(3, 'consultant', 'Nhân viên tư vấn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `price` int(11) NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price`, `update_at`, `update_by`, `create_at`, `status`) VALUES
(1, 'Gội đầu dưỡng sinh', 'Khám & điều trị các bệnh lý cơ bản, chưa rõ nguyên nhân, chưa có định hướng chuyên khoa cụ thể', 0, '2024-06-18 16:16:08', 76, '2024-05-19 12:48:17', 1),
(2, 'Massage ', 'Khám & điều trị các bệnh về da và những phần phụ của da', 0, '2024-11-20 23:00:31', 1, '2024-05-13 12:48:17', 1),
(3, 'Làm nail ngắn', 'Tư vấn chế độ dinh dưỡng cũng như chế độ ăn uống phù hợp cho từng thể trạng và bệnh lý khác nhau ở mọi lứa tuổiLàm nail theo nhu cầu của khách, không úp móng', 0, '2024-06-03 09:22:35', NULL, '2024-05-14 12:48:17', 1),
(4, 'Làm nail dài', 'Làm nail kèm úp móng theo yêu cầu', 0, '2024-06-03 16:55:44', NULL, '2024-05-24 12:48:17', 1),
(38, 'Dịch vụ mới 1', 'Mô tả dịch vụ mới 1', 0, NULL, 1, '2024-11-20 16:08:43', 1),
(39, 'Dịch vụ mới 2', 'Moo tar Dịch vụ mới 1', 300000, '2024-11-21 23:21:00', 1, '2024-11-21 17:15:13', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `time_slots`
--

CREATE TABLE `time_slots` (
  `time_id` int(11) NOT NULL,
  `slot_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `time_slots`
--

INSERT INTO `time_slots` (`time_id`, `slot_time`) VALUES
(1, '08:00:00'),
(2, '08:30:00'),
(3, '09:00:00'),
(4, '09:30:00'),
(5, '10:00:00'),
(6, '10:30:00'),
(7, '11:00:00'),
(8, '13:00:00'),
(9, '13:30:00'),
(10, '14:00:00'),
(11, '14:30:00'),
(12, '15:00:00'),
(13, '15:30:00'),
(14, '16:00:00'),
(15, '16:30:00');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `specialty_id` (`service_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `date_id` (`date_slot`),
  ADD KEY `time_id` (`time_id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`patient_id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `specialty_id` (`service_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Chỉ mục cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`time_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT cho bảng `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `appointments_ibfk_5` FOREIGN KEY (`time_id`) REFERENCES `time_slots` (`time_id`);

--
-- Các ràng buộc cho bảng `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`),
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
