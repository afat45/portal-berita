-- ============================================
-- SUPABASE: CREATE SCHEMA & TABLES
-- Portal Berita Migration from MySQL to PostgreSQL
-- ============================================

-- ============================================
-- STEP 1: Create Custom Schema
-- ============================================

CREATE SCHEMA IF NOT EXISTS portal_berita;

-- Grant permissions to all roles
GRANT USAGE ON SCHEMA portal_berita TO postgres, anon, authenticated, service_role;
GRANT ALL ON ALL TABLES IN SCHEMA portal_berita TO postgres, anon, authenticated, service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA portal_berita TO postgres, anon, authenticated, service_role;
GRANT ALL ON ALL FUNCTIONS IN SCHEMA portal_berita TO postgres, anon, authenticated, service_role;

-- Set default privileges for future objects
ALTER DEFAULT PRIVILEGES IN SCHEMA portal_berita GRANT ALL ON TABLES TO postgres, anon, authenticated, service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA portal_berita GRANT ALL ON SEQUENCES TO postgres, anon, authenticated, service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA portal_berita GRANT ALL ON FUNCTIONS TO postgres, anon, authenticated, service_role;

-- Set search path
SET search_path TO portal_berita;

-- ============================================
-- STEP 2: Create Tables
-- ============================================

-- --------------------------------------------
-- Table: users
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------
-- Table: password_reset_tokens
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------
-- Table: failed_jobs
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------
-- Table: personal_access_tokens
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_personal_access_tokens_tokenable 
ON portal_berita.personal_access_tokens(tokenable_type, tokenable_id);

-- --------------------------------------------
-- Table: categories
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.categories (
    id BIGSERIAL PRIMARY KEY,
    nama_kategori VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------
-- Table: posts
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.posts (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    image VARCHAR(255) NULL,
    content TEXT NOT NULL,
    author VARCHAR(255) NOT NULL,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_category 
        FOREIGN KEY (category_id) 
        REFERENCES portal_berita.categories(id) 
        ON DELETE SET NULL
);

-- --------------------------------------------
-- Table: category_post (Pivot Table)
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.category_post (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL,
    post_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_category_post_category 
        FOREIGN KEY (category_id) 
        REFERENCES portal_berita.categories(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_category_post_post 
        FOREIGN KEY (post_id) 
        REFERENCES portal_berita.posts(id) 
        ON DELETE CASCADE,
    CONSTRAINT unique_category_post 
        UNIQUE(category_id, post_id)
);

-- --------------------------------------------
-- Table: migrations (Laravel tracking)
-- --------------------------------------------
CREATE TABLE IF NOT EXISTS portal_berita.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

-- ============================================
-- STEP 3: Create Indexes for Performance
-- ============================================

CREATE INDEX IF NOT EXISTS idx_posts_slug ON portal_berita.posts(slug);
CREATE INDEX IF NOT EXISTS idx_posts_published_at ON portal_berita.posts(published_at DESC);
CREATE INDEX IF NOT EXISTS idx_posts_category_id ON portal_berita.posts(category_id);
CREATE INDEX IF NOT EXISTS idx_posts_created_at ON portal_berita.posts(created_at DESC);

CREATE INDEX IF NOT EXISTS idx_category_post_category_id ON portal_berita.category_post(category_id);
CREATE INDEX IF NOT EXISTS idx_category_post_post_id ON portal_berita.category_post(post_id);

CREATE INDEX IF NOT EXISTS idx_users_email ON portal_berita.users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON portal_berita.users(role);

-- ============================================
-- STEP 4: Create Updated_at Trigger Function
-- ============================================

CREATE OR REPLACE FUNCTION portal_berita.update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Apply trigger to tables
CREATE TRIGGER update_users_updated_at 
    BEFORE UPDATE ON portal_berita.users 
    FOR EACH ROW EXECUTE FUNCTION portal_berita.update_updated_at_column();

CREATE TRIGGER update_categories_updated_at 
    BEFORE UPDATE ON portal_berita.categories 
    FOR EACH ROW EXECUTE FUNCTION portal_berita.update_updated_at_column();

CREATE TRIGGER update_posts_updated_at 
    BEFORE UPDATE ON portal_berita.posts 
    FOR EACH ROW EXECUTE FUNCTION portal_berita.update_updated_at_column();

-- ============================================
-- STEP 5: Insert Sample Data (Optional)
-- ============================================

-- Sample admin user (password: 'password' hashed)
INSERT INTO portal_berita.users (name, email, password, role, created_at, updated_at)
VALUES (
    'Admin',
    'admin@portalberita.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
) ON CONFLICT (email) DO NOTHING;

-- Sample categories
INSERT INTO portal_berita.categories (nama_kategori, created_at, updated_at) VALUES
    ('Teknologi', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Olahraga', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Politik', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Ekonomi', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
    ('Hiburan', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT DO NOTHING;

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check schema
SELECT current_schema();

-- List all tables
SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'portal_berita'
ORDER BY table_name;

-- Count records
SELECT 
    'users' as table_name, 
    COUNT(*) as record_count 
FROM portal_berita.users
UNION ALL
SELECT 'categories', COUNT(*) FROM portal_berita.categories
UNION ALL
SELECT 'posts', COUNT(*) FROM portal_berita.posts
UNION ALL
SELECT 'category_post', COUNT(*) FROM portal_berita.category_post;

-- ============================================
-- SUCCESS MESSAGE
-- ============================================

DO $$
BEGIN
    RAISE NOTICE '✅ Schema and tables created successfully!';
    RAISE NOTICE '✅ Custom schema: portal_berita';
    RAISE NOTICE '✅ Next step: Update Laravel .env file';
    RAISE NOTICE '✅ Then run: php artisan migrate:fresh --seed';
END $$;
