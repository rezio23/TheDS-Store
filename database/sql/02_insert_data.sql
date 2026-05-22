-- ============================================================================
-- The DS E-Commerce Sample Data (Microsoft SQL Server / T-SQL)
-- ============================================================================
-- Run these INSERT statements AFTER creating the tables.
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. users
-- ----------------------------------------------------------------------------
INSERT INTO users (full_name, email, email_verified_at, password_hash, password, remember_token, phone, gender, is_admin, avatar, address, created_at, updated_at)
VALUES
(N'Test User', N'test@example.com', NULL, N'$2y$12$KQW.WvP1iS4e4jJ5mVqJdOePp7r8s9t0u1v2w3x4y5z6a7b8c9d0e1', N'$2y$12$KQW.WvP1iS4e4jJ5mVqJdOePp7r8s9t0u1v2w3x4y5z6a7b8c9d0e1', NULL, NULL, N'Hidden', 0, NULL, NULL, GETDATE(), GETDATE()),
(N'Admin', N'admin@gmail.com', NULL, N'$2y$12$Admin123HashExampleForSeedingOnlyDoNotUse', N'$2y$12$Admin123HashExampleForSeedingOnlyDoNotUse', NULL, NULL, N'Hidden', 1, NULL, NULL, GETDATE(), GETDATE());

-- ----------------------------------------------------------------------------
-- 2. categories
-- ----------------------------------------------------------------------------
INSERT INTO categories (name, slug, description, image, created_at, updated_at)
VALUES
(N'Clothes', N'clothes', N'Premium clothing for men, women, and kids', N'categories/clothes.jpg', GETDATE(), GETDATE()),
(N'Sneakers', N'sneakers', N'Designer and athletic footwear', N'categories/sneakers.jpg', GETDATE(), GETDATE()),
(N'Bags', N'bags', N'Luxury handbags and backpacks', N'categories/bags.jpg', GETDATE(), GETDATE()),
(N'Perfumes', N'perfumes', N'Exclusive fragrances and colognes', N'categories/perfumes.jpg', GETDATE(), GETDATE()),
(N'Accessories', N'accessories', N'Watches, belts, sunglasses, and more', N'categories/accessories.jpg', GETDATE(), GETDATE());

-- ----------------------------------------------------------------------------
-- 3. products
-- ----------------------------------------------------------------------------
INSERT INTO products (name, slug, brand, description, price, image, gallery, tags, category, badge, rating, stock, created_at, updated_at)
VALUES
(N'Paradigme Eau de Parfum', N'paradigme-eau-de-parfum', N'Basmni', N'A timeless fragrance with notes of amber and vanilla.', 120.00, N'products/paradigme.jpg', N'', N'Popular,Woman,Fragrance', N'perfumes', N'New', N'5', 50, GETDATE(), GETDATE()),
(N'Air Max Pulse', N'air-max-pulse', N'Nike', N'Premium sneakers with Air cushioning technology.', 180.00, N'products/air-max.jpg', N'', N'Popular,Man,Sneaker,Sport', N'sneakers', N'Best Seller', N'4.8', 30, GETDATE(), GETDATE()),
(N'Le City Bag', N'le-city-bag', N'Balenciaga', N'Iconic leather handbag with signature hardware.', 2500.00, N'products/le-city.jpg', N'', N'Popular,Woman,Bag,Luxury', N'bags', N'Premium', N'5', 10, GETDATE(), GETDATE()),
(N'Polo Shirt Classic', N'polo-shirt-classic', N'Ralph Lauren', N'Classic fit polo shirt in premium cotton pique.', 95.00, N'products/polo.jpg', N'', N'Popular,Man,Polo,Classic', N'clothes', N'Classic', N'4.5', 100, GETDATE(), GETDATE()),
(N'Gucci Bloom', N'gucci-bloom', N'Gucci', N'Floral fragrance with notes of jasmine and tuberose.', 145.00, N'products/gucci-bloom.jpg', N'', N'Woman,Fragrance,Luxury', N'perfumes', N'Luxury', N'4.9', 25, GETDATE(), GETDATE()),
(N'Adidas Ultraboost', N'adidas-ultraboost', N'Adidas', N'Responsive running shoes with Boost midsole.', 190.00, N'products/ultraboost.jpg', N'', N'Man,Sneaker,Sport,Streetwear', N'sneakers', N'Sport', N'4.7', 40, GETDATE(), GETDATE()),
(N'Prada Nylon Backpack', N'prada-nylon-backpack', N'Prada', N'Iconic nylon backpack with leather trim.', 1800.00, N'products/prada-backpack.jpg', N'', N'Man,Bag,Accessory,Luxury', N'bags', N'Designer', N'4.8', 15, GETDATE(), GETDATE()),
(N'Puma RS-X', N'puma-rs-x', N'Puma', N'Bold retro-inspired sneakers with chunky sole.', 120.00, N'products/puma-rsx.jpg', N'', N'Kid,Sneaker,Streetwear', N'sneakers', N'Trending', N'4.6', 60, GETDATE(), GETDATE());

-- ----------------------------------------------------------------------------
-- 4. promotions (optional sample)
-- ----------------------------------------------------------------------------
INSERT INTO promotions (code, type, value, min_order, max_uses, uses_count, starts_at, expires_at, is_active, created_at, updated_at)
VALUES
(N'WELCOME10', N'percentage', 10.00, 50.00, 100, 0, GETDATE(), DATEADD(DAY, 30, GETDATE()), 1, GETDATE(), GETDATE()),
(N'SAVE20', N'fixed', 20.00, 100.00, 50, 0, GETDATE(), DATEADD(DAY, 60, GETDATE()), 1, GETDATE(), GETDATE()),
(N'FLASH50', N'percentage', 50.00, 200.00, 10, 0, GETDATE(), DATEADD(DAY, 7, GETDATE()), 1, GETDATE(), GETDATE());
