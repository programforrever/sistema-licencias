CREATE USER IF NOT EXISTS 'admin_test'@'localhost' IDENTIFIED BY 'password123';
INSERT INTO users (name, email, password, created_at, updated_at) VALUES 
('Admin', 'admin@sistema.com', '$2y$12$BvVwXcSzB8TovNJSZGQnj.0pEWLWo3DiX/F4w1L7CqpY4TczQqlMG', NOW(), NOW());
