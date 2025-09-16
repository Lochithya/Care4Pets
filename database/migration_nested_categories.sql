-- Migration: Nested category model (pet types and product types)
-- Safe to run on fresh demo DB created from schema.sql/sample_data.sql

SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;

-- 1) Create new lookup tables
CREATE TABLE IF NOT EXISTS pet_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS product_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
);

-- 2) Seed default values if not present
INSERT IGNORE INTO pet_types (id, name) VALUES
  (1, 'Dogs'),
  (2, 'Cats'),
  (3, 'Birds'),
  (4, 'Small Pets');

INSERT IGNORE INTO product_types (id, name) VALUES
  (1, 'Pets'),
  (2, 'Food'),
  (3, 'Toys'),
  (4, 'Accessories');

-- 3) Build a new products table with the new relationships
CREATE TABLE IF NOT EXISTS products_new (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  stock_quantity INT NOT NULL DEFAULT 0,
  image_url VARCHAR(255),
  pet_type_id INT,
  product_type_id INT,
  CONSTRAINT fk_products_pet_type FOREIGN KEY (pet_type_id) REFERENCES pet_types(id),
  CONSTRAINT fk_products_product_type FOREIGN KEY (product_type_id) REFERENCES product_types(id)
);

-- 4) Copy and transform existing data from products/categories
-- Map categories to pet types by category name keywords
-- Derive product type by product name keywords as a simple demo mapping
INSERT INTO products_new (id, name, description, price, stock_quantity, image_url, pet_type_id, product_type_id)
SELECT 
  p.id,
  p.name,
  p.description,
  p.price,
  p.stock_quantity,
  p.image_url,
  CASE
    WHEN c.name LIKE '%Dog%' THEN 1
    WHEN c.name LIKE '%Cat%' THEN 2
    WHEN c.name LIKE '%Bird%' THEN 3
    ELSE 4
  END AS pet_type_id,
  CASE
    WHEN LOWER(p.name) LIKE '%food%' THEN 2
    WHEN LOWER(p.name) LIKE '%leash%' OR LOWER(p.name) LIKE '%collar%' OR LOWER(p.name) LIKE '%cage%' OR LOWER(p.name) LIKE '%litter%' THEN 4
    WHEN LOWER(p.name) LIKE '%toy%' OR LOWER(p.name) LIKE '%wheel%' OR LOWER(p.name) LIKE '%scratching%' THEN 3
    ELSE 4 -- default to Accessories if unknown
  END AS product_type_id
FROM products p
LEFT JOIN categories c ON p.category_id = c.id;

-- 5) Swap tables
-- Drop foreign-key dependent tables first (temporarily disable integrity)
-- We rely on FOREIGN_KEY_CHECKS=0 to allow swap without resolving constraint names
DROP TABLE IF EXISTS products;
RENAME TABLE products_new TO products;

-- 6) Drop old categories table as it is no longer used
DROP TABLE IF EXISTS categories;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

