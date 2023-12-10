--DROP DATABASE IF EXISTS scandiweb_test_products;
--CREATE DATABASE scandiweb_test_products;
--USE scandiweb_test_products;

-- Table for Types
CREATE TABLE product_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    measure_name VARCHAR(100) NOT NULL,
    measure_unit VARCHAR(50) NOT NULL,
    INDEX (name)
);

-- Table for Type Properties
CREATE TABLE type_properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    CONSTRAINT unique_type_name UNIQUE (type_id, name),
    FOREIGN KEY (type_id) REFERENCES product_types(id) ON DELETE CASCADE,
    INDEX (type_id)
);

-- Table for Products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    type_id INT NOT NULL,
    properties JSON,
    FOREIGN KEY (type_id) REFERENCES product_types(id),
    INDEX (type_id)
);

-- Insert DVD Type
INSERT INTO product_types (name, measure_name, measure_unit) VALUES ('DVD', 'size', 'MB');
-- Insert Book Type
INSERT INTO product_types (name, measure_name, measure_unit) VALUES ('book', 'weight', 'kg');
-- Insert Furniture Type
INSERT INTO product_types (name, measure_name, measure_unit) VALUES ('furniture', 'Dimenions', 'HxWxL');

-- Assuming 'DVD' type has a property named 'Size'
INSERT INTO type_properties (type_id, name, unit)
VALUES ((SELECT id FROM product_types WHERE name = 'DVD'), 'size', 'MB');
-- Assuming 'book' type has a property named 'Weight'
INSERT INTO type_properties (type_id, name, unit)
VALUES ((SELECT id FROM product_types WHERE name = 'book'), 'weight', 'kg');
-- Assuming 'furniture' type has properties named 'Height', 'Width', and 'Length'
INSERT INTO type_properties (type_id, name, unit)
VALUES 
    ((SELECT id FROM product_types WHERE name = 'furniture'), 'height', 'cm'),
    ((SELECT id FROM product_types WHERE name = 'furniture'), 'width', 'cm'),
    ((SELECT id FROM product_types WHERE name = 'furniture'), 'length', 'cm');

-- Insert DVD Product
INSERT INTO products (sku, name, price, type_id, properties)
VALUES ('DVD123', 'DVD Example', 19.99, (SELECT id FROM product_types WHERE name = 'DVD'), '{"size": "120"}');
-- Insert Book Product
INSERT INTO products (sku, name, price, type_id, properties)
VALUES ('BOOK456', 'Book Example', 29.99, (SELECT id FROM product_types WHERE name = 'book'), '{"weight": "1.5"}');
-- Insert Furniture Product
INSERT INTO products (sku, name, price, type_id, properties)
VALUES ('FURN789', 'Furniture Example', 199.99, (SELECT id FROM product_types WHERE name = 'furniture'), '{"height": "120", "width": "80", "length": "180"}');

--SELECT * FROM product_types;
--SELECT * FROM type_properties;
--SELECT * FROM products;
