-- Database: pet_store
--
-- Table: users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    pet_type_id INT NOT NULL ; 
    product_type_id INT NOT NULL ;
    ratings INT NOT NULL ;
    img1 VARCHAR(100),
    img2 VARCHAR(100), 
    img3 VARCHAR(100),
    supplier_id INT NOT NULL ; 
    FOREIGN KEY (pet_type_id) REFERENCES pet_types(id) ;
    FOREIGN KEY (product_type_id) REFERENCES product_types(id) ;
);

--Table : pet_types
CREATE TABLE pet_types(
    id INT AUTO_INCREMENT PRIMARY KEY ;
    name VARCHAR(50) NOT NULL ;
)

--Table : product_types 
CREATE TABLE product_types(
    id INT AUTO_INCREMENT PRIMARY KEY ; 
    name VARCHAR(50) NOT NULL ; 
)

--Table : suppliers
CREATE TABLE suppliers (
    sup_id INT AUTO_INCREMENT PRIMARY KEY,
    sup_name VARCHAR(50) NOT NULL,
    sup_phone VARCHAR(100),
    sup_email VARCHAR(100),
    sup_address VARCHAR(100)
);

-- Table: orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATE DEFAULT CURRENT_DATE ;
    order_time TIME DEFAULT CURRENT_TIME ; 
    delivery_date DATE ;
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table: order_items
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Table: cart (for temporary storage of user's cart items)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Table: shipping (to store the shipping information of a particular order)
CREATE TABLE shipping (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100) NOT NULL,
    phone VARCHAR(20) not NULL,
    add_phone VARCHAR(20),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Table payments(To store the payment information of a particular order)
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_type ENUM('cash', 'card') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NULL,
    payment_time DATE NULL ,

    -- For card payments
    masked_card_number CHAR(16),   -- e.g. 4111********1234
    cardholder_name VARCHAR(100),
    expiry_date CHAR(5),           -- MM/YY
    card_type VARCHAR(30)
    transaction_status VARCHAR(20) ;

    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE messages(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- nullable for guests
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    email VARCHAR(100),
    subject VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);






