-- ============================================================================
-- The DS E-Commerce Database Schema (Microsoft SQL Server / T-SQL)
-- ============================================================================
-- Run these CREATE TABLE statements in order to build the database schema.
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. users
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.users', 'U') IS NOT NULL DROP TABLE dbo.users;
CREATE TABLE users (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    full_name       NVARCHAR(255) NOT NULL,
    email           NVARCHAR(255) NOT NULL UNIQUE,
    email_verified_at DATETIME2 NULL,
    password_hash   NVARCHAR(255) NOT NULL,
    password        NVARCHAR(255) NULL,
    remember_token  NVARCHAR(100) NULL,
    phone           NVARCHAR(255) NULL,
    gender          NVARCHAR(255) NOT NULL DEFAULT N'Hidden',
    is_admin        BIT NOT NULL DEFAULT 0,
    avatar          NVARCHAR(255) NULL,
    address         NVARCHAR(MAX) NULL,
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL
);

-- ----------------------------------------------------------------------------
-- 2. password_reset_tokens
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.password_reset_tokens', 'U') IS NOT NULL DROP TABLE dbo.password_reset_tokens;
CREATE TABLE password_reset_tokens (
    email           NVARCHAR(255) PRIMARY KEY,
    token           NVARCHAR(255) NOT NULL,
    created_at      DATETIME2 NULL
);

-- ----------------------------------------------------------------------------
-- 3. categories
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.categories', 'U') IS NOT NULL DROP TABLE dbo.categories;
CREATE TABLE categories (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    name            NVARCHAR(255) NOT NULL,
    slug            NVARCHAR(255) NOT NULL UNIQUE,
    description     NVARCHAR(MAX) NULL,
    image           NVARCHAR(255) NULL,
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL
);

-- ----------------------------------------------------------------------------
-- 4. products
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.products', 'U') IS NOT NULL DROP TABLE dbo.products;
CREATE TABLE products (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    name            NVARCHAR(255) NOT NULL,
    slug            NVARCHAR(255) NOT NULL UNIQUE,
    brand           NVARCHAR(255) NOT NULL,
    description     NVARCHAR(MAX) NULL,
    price           DECIMAL(10, 2) NOT NULL,
    image           NVARCHAR(255) NULL,
    gallery         NVARCHAR(MAX) NULL,
    tags            NVARCHAR(255) NULL,
    category        NVARCHAR(255) NULL,
    badge           NVARCHAR(255) NULL,
    rating          NVARCHAR(255) NULL,
    stock           INT NOT NULL DEFAULT 0,
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL
);

-- ----------------------------------------------------------------------------
-- 5. favorites
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.favorites', 'U') IS NOT NULL DROP TABLE dbo.favorites;
CREATE TABLE favorites (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    product_id      BIGINT NOT NULL,
    created_at      DATETIME2 NULL,
    CONSTRAINT favorites_user_product_unique UNIQUE (user_id, product_id),
    CONSTRAINT favorites_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT favorites_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 6. promotions
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.promotions', 'U') IS NOT NULL DROP TABLE dbo.promotions;
CREATE TABLE promotions (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    code            NVARCHAR(255) NOT NULL UNIQUE,
    type            NVARCHAR(50) NOT NULL DEFAULT N'percentage',
    value           DECIMAL(10, 2) NOT NULL DEFAULT 0,
    min_order       DECIMAL(10, 2) NULL,
    max_uses        INT NULL,
    uses_count      INT NOT NULL DEFAULT 0,
    starts_at       DATETIME2 NULL,
    expires_at      DATETIME2 NULL,
    is_active       BIT NOT NULL DEFAULT 1,
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL,
    CONSTRAINT chk_promotion_type CHECK (type IN (N'percentage', N'fixed'))
);

-- ----------------------------------------------------------------------------
-- 7. orders
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.orders', 'U') IS NOT NULL DROP TABLE dbo.orders;
CREATE TABLE orders (
    id                  BIGINT IDENTITY(1,1) PRIMARY KEY,
    user_id             BIGINT NOT NULL,
    promotion_id        BIGINT NULL,
    total               DECIMAL(10, 2) NOT NULL,
    discount            DECIMAL(10, 2) NOT NULL DEFAULT 0,
    status              NVARCHAR(255) NOT NULL DEFAULT N'pending',
    shipping_name       NVARCHAR(255) NOT NULL,
    shipping_phone      NVARCHAR(255) NOT NULL,
    shipping_address    NVARCHAR(MAX) NOT NULL,
    shipping_postal     NVARCHAR(255) NOT NULL,
    shipping_email      NVARCHAR(255) NOT NULL,
    shipping_mode       NVARCHAR(255) NOT NULL,
    created_at          DATETIME2 NULL,
    updated_at          DATETIME2 NULL,
    CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT orders_promotion_id_foreign FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL
);

-- ----------------------------------------------------------------------------
-- 8. order_items
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.order_items', 'U') IS NOT NULL DROP TABLE dbo.order_items;
CREATE TABLE order_items (
    id                  BIGINT IDENTITY(1,1) PRIMARY KEY,
    order_id            BIGINT NOT NULL,
    product_name        NVARCHAR(255) NOT NULL,
    product_brand       NVARCHAR(255) NOT NULL,
    product_price       DECIMAL(10, 2) NOT NULL,
    quantity            INT NOT NULL DEFAULT 1,
    size                NVARCHAR(255) NULL,
    product_image       NVARCHAR(255) NULL,
    created_at          DATETIME2 NULL,
    CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 9. notifications
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.notifications', 'U') IS NOT NULL DROP TABLE dbo.notifications;
CREATE TABLE notifications (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    title           NVARCHAR(255) NOT NULL,
    message         NVARCHAR(MAX) NULL,
    type            NVARCHAR(255) NOT NULL DEFAULT N'general',
    link            NVARCHAR(255) NULL,
    image           NVARCHAR(255) NULL,
    read_at         DATETIME2 NULL,
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL,
    CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------------------------
-- 10. user_requests (Help Center / Contact submissions)
-- ----------------------------------------------------------------------------
IF OBJECT_ID('dbo.user_requests', 'U') IS NOT NULL DROP TABLE dbo.user_requests;
CREATE TABLE user_requests (
    id              BIGINT IDENTITY(1,1) PRIMARY KEY,
    user_id         BIGINT NULL,
    full_name       NVARCHAR(255) NULL,
    phone           NVARCHAR(255) NULL,
    email           NVARCHAR(255) NULL,
    subject         NVARCHAR(255) NULL,
    message         NVARCHAR(MAX) NULL,
    attachment      NVARCHAR(255) NULL,
    status          NVARCHAR(255) NULL DEFAULT N'pending',
    created_at      DATETIME2 NULL,
    updated_at      DATETIME2 NULL,
    CONSTRAINT user_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
