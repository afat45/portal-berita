-- ============================================
-- SUPABASE TEST QUERIES
-- Portal Berita - Verification & Testing
-- ============================================

-- ============================================
-- 1. VERIFY SCHEMA & TABLES
-- ============================================

-- Check if custom schema exists
SELECT schema_name 
FROM information_schema.schemata 
WHERE schema_name = 'portal_berita';

-- List all tables in custom schema
SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'portal_berita'
ORDER BY table_name;

-- Check current schema
SELECT current_schema();

-- ============================================
-- 2. VERIFY RLS STATUS
-- ============================================

-- Check RLS status for all tables
SELECT 
    schemaname,
    tablename,
    rowsecurity as rls_enabled
FROM pg_tables
WHERE schemaname = 'portal_berita'
ORDER BY tablename;

-- Check active policies
SELECT 
    schemaname,
    tablename,
    policyname,
    permissive,
    roles,
    cmd as command
FROM pg_policies
WHERE schemaname = 'portal_berita'
ORDER BY tablename, policyname;

-- ============================================
-- 3. DATA VERIFICATION
-- ============================================

-- Count records in each table
SELECT 'posts' as table_name, COUNT(*) as total 
FROM portal_berita.posts
UNION ALL
SELECT 'categories', COUNT(*) 
FROM portal_berita.categories
UNION ALL
SELECT 'users', COUNT(*) 
FROM portal_berita.users
UNION ALL
SELECT 'category_post', COUNT(*) 
FROM portal_berita.category_post;

-- Get sample posts with categories
SELECT 
    p.id,
    p.title,
    p.slug,
    p.author,
    p.published_at,
    COALESCE(
        json_agg(
            json_build_object(
                'id', c.id,
                'nama_kategori', c.nama_kategori
            )
        ) FILTER (WHERE c.id IS NOT NULL),
        '[]'
    ) as categories
FROM portal_berita.posts p
LEFT JOIN portal_berita.category_post cp ON p.id = cp.post_id
LEFT JOIN portal_berita.categories c ON cp.category_id = c.id
GROUP BY p.id, p.title, p.slug, p.author, p.published_at
ORDER BY p.published_at DESC
LIMIT 5;

-- Get latest posts
SELECT 
    id,
    title,
    slug,
    author,
    published_at
FROM portal_berita.posts
ORDER BY published_at DESC
LIMIT 10;

-- Get all categories
SELECT 
    id,
    nama_kategori,
    created_at
FROM portal_berita.categories
ORDER BY nama_kategori;

-- ============================================
-- 4. TEST RLS POLICIES
-- ============================================

-- This should work (public read access)
SET ROLE anon;
SELECT COUNT(*) FROM portal_berita.posts;
RESET ROLE;

-- This should fail (anon cannot insert)
-- SET ROLE anon;
-- INSERT INTO portal_berita.posts (title, content, author) 
-- VALUES ('Test', 'Content', 'Author');
-- RESET ROLE;

-- ============================================
-- 5. RELATIONSHIP TESTING
-- ============================================

-- Test posts with their categories (many-to-many)
SELECT 
    p.id as post_id,
    p.title,
    c.id as category_id,
    c.nama_kategori
FROM portal_berita.posts p
JOIN portal_berita.category_post cp ON p.id = cp.post_id
JOIN portal_berita.categories c ON cp.category_id = c.id
ORDER BY p.published_at DESC
LIMIT 10;

-- Count posts per category
SELECT 
    c.nama_kategori,
    COUNT(cp.post_id) as post_count
FROM portal_berita.categories c
LEFT JOIN portal_berita.category_post cp ON c.id = cp.category_id
GROUP BY c.id, c.nama_kategori
ORDER BY post_count DESC;

-- ============================================
-- 6. PERFORMANCE CHECKS
-- ============================================

-- Check indexes
SELECT 
    tablename,
    indexname,
    indexdef
FROM pg_indexes
WHERE schemaname = 'portal_berita'
ORDER BY tablename, indexname;

-- Check table sizes
SELECT 
    schemaname,
    tablename,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size
FROM pg_tables
WHERE schemaname = 'portal_berita'
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;

-- ============================================
-- 7. SEARCH TESTING (for Frontend)
-- ============================================

-- Search posts by title
SELECT id, title, slug
FROM portal_berita.posts
WHERE title ILIKE '%news%'
LIMIT 5;

-- Full text search (title or content)
SELECT id, title, slug
FROM portal_berita.posts
WHERE title ILIKE '%laravel%' OR content ILIKE '%laravel%'
LIMIT 5;

-- ============================================
-- 8. DATE FILTERING (for Frontend)
-- ============================================

-- Posts from last 30 days
SELECT id, title, published_at
FROM portal_berita.posts
WHERE published_at >= NOW() - INTERVAL '30 days'
ORDER BY published_at DESC;

-- Posts grouped by month
SELECT 
    DATE_TRUNC('month', published_at) as month,
    COUNT(*) as post_count
FROM portal_berita.posts
GROUP BY DATE_TRUNC('month', published_at)
ORDER BY month DESC;

-- ============================================
-- 9. API ENDPOINT SIMULATION
-- ============================================

-- Simulate: GET /api/posts (with pagination)
SELECT 
    id,
    title,
    slug,
    image,
    LEFT(content, 200) as excerpt,
    author,
    published_at
FROM portal_berita.posts
ORDER BY published_at DESC
LIMIT 10 OFFSET 0;

-- Simulate: GET /api/posts/:slug
SELECT 
    p.*,
    json_agg(
        json_build_object(
            'id', c.id,
            'nama_kategori', c.nama_kategori
        )
    ) as categories
FROM portal_berita.posts p
LEFT JOIN portal_berita.category_post cp ON p.id = cp.post_id
LEFT JOIN portal_berita.categories c ON cp.category_id = c.id
WHERE p.slug = 'your-slug-here'
GROUP BY p.id;

-- ============================================
-- 10. CLEANUP & MAINTENANCE
-- ============================================

-- Vacuum analyze (run periodically)
-- VACUUM ANALYZE portal_berita.posts;
-- VACUUM ANALYZE portal_berita.categories;

-- Refresh statistics
-- ANALYZE portal_berita.posts;
-- ANALYZE portal_berita.categories;
