-- Insert sample categories
INSERT INTO categories (name) VALUES 
('Dog Products'),
('Cat Products'),
('Bird Products'),
('Small Pet Products');

-- Insert sample products
INSERT INTO products (name, description, price, stock_quantity, image_url, category_id) VALUES 
('Premium Dog Food', 'High-quality dry dog food with natural ingredients for optimal nutrition', 29.99, 50, '../images/dog_food.jpg', 1),
('Natural Cat Food', 'Nutritious cat food made with real meat and vegetables', 24.99, 45, '../images/cat_food.jpg', 2),
('Waterproof Dog Collar', 'Durable and comfortable waterproof collar for dogs of all sizes', 15.99, 30, '../images/dog_collar.jpg', 1),
('Premium Dog Leash', 'Strong and stylish leash perfect for daily walks', 19.99, 25, '../images/dog_leash.jpg', 1),
('Cat Scratching Post', 'Multi-level scratching post to keep your cat entertained', 39.99, 20, '../images/cat_toy.jpg', 2),
('Bird Cage Large', 'Spacious cage perfect for medium to large birds', 89.99, 15, '../images/bird_cage.jpg', 3),
('Hamster Wheel', 'Silent running wheel for hamsters and small pets', 12.99, 40, '../images/hamster_wheel.jpg', 4),
('Dog Toy Set', 'Set of 5 interactive toys to keep your dog engaged', 22.99, 35, '../images/dog_toys.jpg', 1),
('Cat Litter Premium', 'Odor-control cat litter that clumps for easy cleaning', 16.99, 60, '../images/cat_litter.jpg', 2),
('Bird Food Mix', 'Nutritious seed mix for various bird species', 8.99, 55, '../images/bird_food.jpg', 3);

--Insert supplier details
INSERT INTO suppliers (sup_name, sup_phone, sup_email, sup_address) VALUES
('Happy Paws Supplies', '0712345678', 'info@happypaws.com', '45 Pet Street, Colombo'),
('Furry Friends Co.', '0778765432', 'support@furryfriends.com', '12 Bark Avenue, Kandy'),
('Whiskers & Tails', '0751122334', 'sales@whiskersntails.com', '99 Cat Lane, Galle'),
('PetWorld Traders', '0789988776', 'contact@petworld.com', '150 Doggo Road, Jaffna'),
('Pawfect Choice', '0723344556', 'hello@pawfectchoice.com', '23 Rabbit Park, Negombo'),
('AnimalCare Hub', '0744455667', 'service@animalcarehub.com', '80 Birdy Street, Matara');
