CREATE DATABASE IF NOT EXISTS unitrade;
USE unitrade;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telegram_username` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL,
  `ugr_id` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `role` enum('student','admin') DEFAULT 'student',
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `condition_type` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `image` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data

-- Categories
INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `image`) VALUES
(1, 'Electronics', 'Laptops, phones, accessories', 'bi-laptop', 'electronics.png'),
(2, 'Stationery', 'Books, pens, geometry sets', 'bi-journal-text', 'stationery.png'),
(3, 'Clothes', 'T-shirts, hoodies, jackets', 'bi-bag', 'clothes.png'),
(4, 'Shoes', 'Sneakers, formal shoes', 'bi-cart2', 'shoes.png'),
(5, 'Dorm Essentials', 'Mattresses, blankets, buckets', 'bi-house-door', 'dorm.png'),
(6, 'Food & Beverage', 'Meals, snacks, drinks', 'bi-cup-straw', 'food.svg');

-- Admin and Sample Users (Passwords are 'password123')
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `telegram_username`, `phone`, `department`, `ugr_id`, `role`, `is_verified`) VALUES
(1, 'Admin User', 'admin.unitrade@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '@admin', '+251911111111', 'CSE', 'UGR/00000/15', 'admin', 1),
(2, 'Abebe Kebede', 'abebe@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '@abebe_k', '+251922222222', 'SE', 'UGR/12345/15', 'student', 1),
(3, 'Sara Tadesse', 'sara@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '@sara_t', '+251933333333', 'ECE', 'UGR/67890/15', 'student', 1);

-- Sample Items
INSERT INTO `items` (`id`, `user_id`, `category_id`, `title`, `description`, `price`, `condition_type`, `status`, `image`) VALUES
(1, 2, 1, 'Dell Laptop i5', 'Dell Inspiron i5 8th gen, 8GB RAM, 256GB SSD. Good battery life.', 18500.00, 'Used', 'approved', 'default-laptop.svg'),
(2, 3, 1, 'HP Laptop i3', 'HP 15, i3 10th gen, 4GB RAM, 1TB HDD.', 15000.00, 'Like New', 'approved', 'default-laptop.svg'),
(3, 2, 1, 'Samsung Galaxy A12', 'Samsung A12, 64GB storage, screen slightly cracked but works fine.', 6500.00, 'Used', 'approved', 'default-phone.svg'),
(4, 3, 2, 'Geometry Set', 'Full geometry set, barely used.', 250.00, 'Like New', 'approved', 'default-stationery.svg'),
(5, 2, 2, 'Pens Bundle', 'Pack of 10 blue BIC pens.', 100.00, 'New', 'approved', 'default-stationery.svg'),
(6, 3, 2, 'Notebook Pack', 'Pack of 5 squared notebooks, 100 leaves.', 300.00, 'New', 'approved', 'default-stationery.svg'),
(7, 2, 3, 'Hoodie Black', 'Warm black hoodie, size M. Perfect for cold Adama nights.', 800.00, 'Like New', 'approved', 'default-clothes.svg'),
(8, 3, 3, 'Jacket Winter', 'Thick winter jacket, waterproof. Size L.', 1200.00, 'Used', 'approved', 'default-clothes.svg'),
(9, 2, 4, 'Nike Running Shoes', 'Nike Joyride running shoes, size 42. Extremely comfortable.', 2500.00, 'Used', 'approved', 'default-shoes.svg'),
(10, 3, 4, 'Adidas Sneakers', 'White Adidas Stan Smith, size 41.', 1800.00, 'Like New', 'approved', 'default-shoes.svg'),
(11, 2, 6, 'በርገር ኮምቦ', 'Delicious beef burger with fries and Coca Cola.', 350.00, 'New', 'approved', 'default-food.svg'),
(12, 3, 6, 'እንጀራ እና ወጥ', 'Traditional homemade injera with doro wot.', 450.00, 'New', 'approved', 'default-food.svg'),
(13, 2, 6, 'ፍርፍር', 'Spicy firfir with egg.', 200.00, 'New', 'approved', 'default-food.svg'),
(14, 3, 6, 'ኮካ ኮላ', 'Cold 500ml Coca Cola plastic bottle.', 40.00, 'New', 'approved', 'default-food.svg'),
(15, 2, 5, 'Single Mattress', 'Comfortable sponge mattress for dorm beds.', 1500.00, 'Used', 'approved', 'default-dorm.svg'),
(16, 3, 5, 'Plastic Bucket', '20L strong plastic bucket for washing clothes.', 150.00, 'New', 'approved', 'default-dorm.svg'),
(17, 2, 1, 'MacBook Pro 2019', 'Core i7, 16GB RAM, 512GB SSD. Mint condition.', 45000.00, 'Like New', 'approved', 'default-laptop.svg'),
(18, 3, 3, 'ASTU Official T-Shirt', 'Official white T-shirt from last year event, size M.', 350.00, 'Used', 'approved', 'default-clothes.svg'),
(19, 2, 2, 'Engineering Drawing Board', 'Standard A3 size drawing board with T-square.', 1200.00, 'Like New', 'pending', 'default-stationery.svg'),
(20, 3, 6, 'Shiro Tegabino', 'Hot shiro served with 2 injeras. Delivery around Block 50.', 150.00, 'New', 'approved', 'default-food.svg');
