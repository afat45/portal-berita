-- ============================================
-- SUPABASE RLS (ROW LEVEL SECURITY) SETUP
-- Portal Berita - Custom Schema: portal_berita
-- ============================================

-- ============================================
-- 1. TABEL POSTS
-- ============================================

-- Enable RLS
ALTER TABLE portal_berita.posts ENABLE ROW LEVEL SECURITY;

-- Drop existing policies (jika ada)
DROP POLICY IF EXISTS "Allow public read access" ON portal_berita.posts;
DROP POLICY IF EXISTS "Allow authenticated insert" ON portal_berita.posts;
DROP POLICY IF EXISTS "Allow authenticated update" ON portal_berita.posts;
DROP POLICY IF EXISTS "Allow authenticated delete" ON portal_berita.posts;

-- Policy: Public dapat membaca semua posts
CREATE POLICY "Allow public read access"
ON portal_berita.posts
FOR SELECT
TO public
USING (true);

-- Policy: Authenticated user dapat insert posts
CREATE POLICY "Allow authenticated insert"
ON portal_berita.posts
FOR INSERT
TO authenticated
WITH CHECK (true);

-- Policy: Authenticated user dapat update posts
CREATE POLICY "Allow authenticated update"
ON portal_berita.posts
FOR UPDATE
TO authenticated
USING (true)
WITH CHECK (true);

-- Policy: Authenticated user dapat delete posts
CREATE POLICY "Allow authenticated delete"
ON portal_berita.posts
FOR DELETE
TO authenticated
USING (true);

-- ============================================
-- 2. TABEL CATEGORIES
-- ============================================

-- Enable RLS
ALTER TABLE portal_berita.categories ENABLE ROW LEVEL SECURITY;

-- Drop existing policies
DROP POLICY IF EXISTS "Allow public read categories" ON portal_berita.categories;
DROP POLICY IF EXISTS "Allow authenticated manage categories" ON portal_berita.categories;

-- Policy: Public dapat membaca categories
CREATE POLICY "Allow public read categories"
ON portal_berita.categories
FOR SELECT
TO public
USING (true);

-- Policy: Authenticated user dapat manage categories
CREATE POLICY "Allow authenticated manage categories"
ON portal_berita.categories
FOR ALL
TO authenticated
USING (true)
WITH CHECK (true);

-- ============================================
-- 3. TABEL CATEGORY_POST (Pivot Table)
-- ============================================

-- Enable RLS
ALTER TABLE portal_berita.category_post ENABLE ROW LEVEL SECURITY;

-- Drop existing policies
DROP POLICY IF EXISTS "Allow public read category_post" ON portal_berita.category_post;
DROP POLICY IF EXISTS "Allow authenticated manage category_post" ON portal_berita.category_post;

-- Policy: Public dapat membaca pivot data
CREATE POLICY "Allow public read category_post"
ON portal_berita.category_post
FOR SELECT
TO public
USING (true);

-- Policy: Authenticated user dapat manage pivot
CREATE POLICY "Allow authenticated manage category_post"
ON portal_berita.category_post
FOR ALL
TO authenticated
USING (true)
WITH CHECK (true);

-- ============================================
-- 4. TABEL USERS (Jika ada)
-- ============================================

-- Enable RLS
ALTER TABLE portal_berita.users ENABLE ROW LEVEL SECURITY;

-- Drop existing policies
DROP POLICY IF EXISTS "Users can view their own profile" ON portal_berita.users;

-- Policy: User hanya bisa lihat profile sendiri
CREATE POLICY "Users can view their own profile"
ON portal_berita.users
FOR SELECT
TO authenticated
USING (auth.uid()::text = id::text);

-- ============================================
-- VERIFIKASI RLS
-- ============================================

-- Check RLS status untuk semua tabel
SELECT 
    schemaname,
    tablename,
    rowsecurity as rls_enabled
FROM pg_tables
WHERE schemaname = 'portal_berita'
ORDER BY tablename;

-- Check policies yang aktif
SELECT 
    schemaname,
    tablename,
    policyname,
    permissive,
    roles,
    cmd
FROM pg_policies
WHERE schemaname = 'portal_berita'
ORDER BY tablename, policyname;

-- ============================================
-- TESTING QUERIES (Optional)
-- ============================================

-- Test public access (akan berhasil)
-- SELECT * FROM portal_berita.posts LIMIT 5;

-- Test insert sebagai anon (akan gagal karena RLS)
-- INSERT INTO portal_berita.posts (title, content) VALUES ('Test', 'Content');
