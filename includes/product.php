<?php
require_once 'config.php';

// Get all products with pet type and product type names
function getAllProducts() {
    $conn = getConnection();
    
    $sql = "SELECT p.*, pt.name as pet_type_name, prt.name as product_type_name 
            FROM products p 
            LEFT JOIN pet_types pt ON p.pet_type_id = pt.id
            LEFT JOIN product_types prt ON p.product_type_id = prt.id";
    $result = $conn->query($sql);
    
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    $conn->close();
    return $products;
}

// Get products by pet type and product type
function getProductsByFilters($petTypeId , $productTypeId ) {
    $conn = getConnection();
    
    $sql = "SELECT p.*, pt.name as pet_type_name, prt.name as product_type_name 
            FROM products p 
            LEFT JOIN pet_types pt ON p.pet_type_id = pt.id
            LEFT JOIN product_types prt ON p.product_type_id = prt.id WHERE 1=1";

    $params = [];                   // for adding of paramters for the sql based on pet,product IDS
    $types = '';                    // necessary variable for bind_param() function

    if ($petTypeId !== null) {                 // If not specially selected in the website, this is not executed . so default
        $sql .= " AND p.pet_type_id = ?";
        $params[] = $petTypeId;
        $types .= 'i';                        
    }

    if ($productTypeId !== null) {              // If not specially selected in the website, this is not executed . so default
        $sql .= " AND p.product_type_id = ?";
        $params[] = $productTypeId;
        $types .= 'i';
    }

    $stmt = $conn->prepare($sql);                  // preparing the sql statement with placeholders
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);         // $params assigned for placeholders(?)  
    }
    $stmt->execute();
    $result = $stmt->get_result();                     // get result from the database
    
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $products;
}

// Get single product by ID
function getProductById($productId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT p.*, pt.name as pet_type_name, prt.name as product_type_name 
                           FROM products p 
                           LEFT JOIN pet_types pt ON p.pet_type_id = pt.id
                           LEFT JOIN product_types prt ON p.product_type_id = prt.id 
                           WHERE p.id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $product = null;
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();
    return $product;
}

// Get all pet types
function getAllPetTypes() {
    $conn = getConnection();
    
    $sql = "SELECT * FROM pet_types";
    $result = $conn->query($sql);
    
    $petTypes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $petTypes[] = $row;
        }
    }
    
    $conn->close();
    return $petTypes;
}

// Get all product types
function getAllProductTypes() {
    $conn = getConnection();
    
    $sql = "SELECT * FROM product_types";
    $result = $conn->query($sql);
    
    $productTypes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $productTypes[] = $row;
        }
    }
    
    $conn->close();
    return $productTypes;
}

// Add product (admin function) - updated to use new types
function addProduct($name, $description, $price, $stockQuantity, $imageUrl, $petTypeId, $productTypeId) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url, pet_type_id, product_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisii", $name, $description, $price, $stockQuantity, $imageUrl, $petTypeId, $productTypeId);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}
?>

