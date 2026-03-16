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
/* === Replace the existing getProductsByFilters(...) with this === */
function getProductsByFilters($petTypeId, $productTypeId, $sort = 'default') {
    $conn = getConnection();

    $sql = "SELECT p.*, pt.name as pet_type_name, prt.name as product_type_name
            FROM products p
            LEFT JOIN pet_types pt ON p.pet_type_id = pt.id
            LEFT JOIN product_types prt ON p.product_type_id = prt.id
            WHERE 1=1";

    $params = [];
    $types = '';

    if ($petTypeId !== null) {
        $sql .= " AND p.pet_type_id = ?";
        $params[] = $petTypeId;
        $types .= 'i';
    }

    if ($productTypeId !== null) {
        $sql .= " AND p.product_type_id = ?";
        $params[] = $productTypeId;
        $types .= 'i';
    }

    // Add sorting (safe — we only use controlled values)
    if ($sort === 'low') {
        $sql .= " ORDER BY p.price ASC";
    } elseif ($sort === 'high') {
        $sql .= " ORDER BY p.price DESC";
    } else {
        // default ordering (you can change to created_at or id)
        $sql .= " ORDER BY p.id DESC";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // prepare failed, return empty array (or log error)
        error_log("Prepare failed in getProductsByFilters: " . $conn->error);
        $conn->close();
        return [];
    }

    if (!empty($params)) {
        // uses argument unpacking (PHP 5.6+) to bind dynamically
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $stmt->close();
    $conn->close();
    return $products;
}

/* === Add this helper to allow searching products by name/description === */
function searchProducts($query, $limit = 50) {
    $conn = getConnection();

    $sql = "SELECT p.*, pt.name as pet_type_name, prt.name as product_type_name
            FROM products p
            LEFT JOIN pet_types pt ON p.pet_type_id = pt.id
            LEFT JOIN product_types prt ON p.product_type_id = prt.id
            WHERE p.name LIKE ? OR p.description LIKE ?
            LIMIT ?";

    $like = '%' . $query . '%';
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed in searchProducts: " . $conn->error);
        $conn->close();
        return [];
    }

    $stmt->bind_param('ssi', $like, $like, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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