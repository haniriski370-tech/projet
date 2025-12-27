CREATE DATABASE projet;
USE projet;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100),
email VARCHAR(150) UNIQUE,
password VARCHAR(255),
created_at TIMESTAMP DEFAULT current_timestamp
);

CREATE TABLE products (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(200) NOT NULL,
price DECIMAL(10,2) NOT NULL,
image_url TEXT,
created_at TIMESTAMP DEFAULT current_timestamp
);

CREATE TABLE carts (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NULL,
created_at TIMESTAMP DEFAULT current_timestamp,
FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE carts (
id INT AUTO_INCREMENT PRIMARY KEY,
cart_id INT ,
product_id INT ,
quantity INT DEFAULT 1,
FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
FOREIGN KEY (product_id) REFERENCES products(id)
);


CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NULL,
total DECIMAL (10,2) ,
status
ENUM ('pending','paid','cancelled')
DEFAULT 'pending',
created_at TIMESTAMP DEFAULT current_timestamp,
FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT ,
product_id INT ,
price DECIMAL(10.2),
quantity INT ,
FOREIGN KEY (order_id) REFERENCES orders(id) ,
FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE payments (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT ,
method ENUM ('visa','paypal') ,
amount DECIMAL(10.2),
status ENUM ('success','failed'),
created_at TIMESTAMP DEFAULT current_timestamp,
FOREIGN KEY (order_id) REFERENCES orders(id)
);


