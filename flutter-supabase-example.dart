// ============================================
// FLUTTER + SUPABASE INTEGRATION
// Portal Berita - Example Implementation
// ============================================

// --------------------------------------------
// 1. SETUP DEPENDENCIES
// File: pubspec.yaml
// --------------------------------------------
/*
dependencies:
  flutter:
    sdk: flutter
  supabase_flutter: ^2.0.0
  intl: ^0.18.0  # For date formatting
*/

// --------------------------------------------
// 2. INITIALIZE SUPABASE
// File: lib/main.dart
// --------------------------------------------
import 'package:flutter/material.dart';
import 'package:supabase_flutter/supabase_flutter.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  await Supabase.initialize(
    url: 'https://alpivotfqqqsjhdovlrj.supabase.co',
    anonKey: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFscGl2b3RmcXFxc2poZG92bHJqIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA2MDgxNzksImV4cCI6MjA4NjE4NDE3OX0.tnbqAkJSHJ1IF597D2PpfY3X2DmBFXZbX_frW5qZpck',
  );
  
  runApp(const MyApp());
}

// Global Supabase client
final supabase = Supabase.instance.client;

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Portal Berita',
      theme: ThemeData(
        primarySwatch: Colors.blue,
        useMaterial3: true,
      ),
      home: const PostsListScreen(),
    );
  }
}

// --------------------------------------------
// 3. POST MODEL
// File: lib/models/post.dart
// --------------------------------------------
class Post {
  final int id;
  final String title;
  final String slug;
  final String? image;
  final String content;
  final String author;
  final DateTime publishedAt;
  final List<Category> categories;

  Post({
    required this.id,
    required this.title,
    required this.slug,
    this.image,
    required this.content,
    required this.author,
    required this.publishedAt,
    this.categories = const [],
  });

  factory Post.fromJson(Map<String, dynamic> json) {
    return Post(
      id: json['id'],
      title: json['title'],
      slug: json['slug'],
      image: json['image'],
      content: json['content'],
      author: json['author'],
      publishedAt: DateTime.parse(json['published_at']),
      categories: (json['categories'] as List?)
          ?.map((cat) => Category.fromJson(cat))
          .toList() ?? [],
    );
  }
}

class Category {
  final int id;
  final String namaKategori;

  Category({
    required this.id,
    required this.namaKategori,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      namaKategori: json['nama_kategori'],
    );
  }
}

// --------------------------------------------
// 4. POST SERVICE
// File: lib/services/post_service.dart
// --------------------------------------------
class PostService {
  // Fetch all posts
  static Future<List<Post>> getPosts() async {
    try {
      final response = await supabase
          .schema('portal_berita')  // ✅ Custom schema
          .from('posts')
          .select('*, categories(*)')
          .order('published_at', ascending: false);

      return (response as List)
          .map((json) => Post.fromJson(json))
          .toList();
    } catch (e) {
      throw Exception('Error fetching posts: $e');
    }
  }

  // Fetch single post by slug
  static Future<Post> getPostBySlug(String slug) async {
    try {
      final response = await supabase
          .schema('portal_berita')
          .from('posts')
          .select('*, categories(*)')
          .eq('slug', slug)
          .single();

      return Post.fromJson(response);
    } catch (e) {
      throw Exception('Error fetching post: $e');
    }
  }

  // Fetch posts by category
  static Future<List<Post>> getPostsByCategory(int categoryId) async {
    try {
      final response = await supabase
          .schema('portal_berita')
          .from('posts')
          .select('*, categories!inner(*)')
          .eq('categories.id', categoryId)
          .order('published_at', ascending: false);

      return (response as List)
          .map((json) => Post.fromJson(json))
          .toList();
    } catch (e) {
      throw Exception('Error fetching posts by category: $e');
    }
  }

  // Search posts
  static Future<List<Post>> searchPosts(String query) async {
    try {
      final response = await supabase
          .schema('portal_berita')
          .from('posts')
          .select('*')
          .or('title.ilike.%$query%,content.ilike.%$query%')
          .order('published_at', ascending: false);

      return (response as List)
          .map((json) => Post.fromJson(json))
          .toList();
    } catch (e) {
      throw Exception('Error searching posts: $e');
    }
  }

  // Get latest posts (limit 10)
  static Future<List<Post>> getLatestPosts() async {
    try {
      final response = await supabase
          .schema('portal_berita')
          .from('posts')
          .select('*, categories(*)')
          .order('published_at', ascending: false)
          .limit(10);

      return (response as List)
          .map((json) => Post.fromJson(json))
          .toList();
    } catch (e) {
      throw Exception('Error fetching latest posts: $e');
    }
  }
}

// --------------------------------------------
// 5. POSTS LIST SCREEN
// File: lib/screens/posts_list_screen.dart
// --------------------------------------------
class PostsListScreen extends StatefulWidget {
  const PostsListScreen({super.key});

  @override
  State<PostsListScreen> createState() => _PostsListScreenState();
}

class _PostsListScreenState extends State<PostsListScreen> {
  List<Post> posts = [];
  bool isLoading = true;
  String? error;

  @override
  void initState() {
    super.initState();
    _loadPosts();
  }

  Future<void> _loadPosts() async {
    setState(() {
      isLoading = true;
      error = null;
    });

    try {
      final fetchedPosts = await PostService.getPosts();
      setState(() {
        posts = fetchedPosts;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        error = e.toString();
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Portal Berita'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadPosts,
          ),
        ],
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (error != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text('Error: $error'),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _loadPosts,
              child: const Text('Retry'),
            ),
          ],
        ),
      );
    }

    if (posts.isEmpty) {
      return const Center(child: Text('No posts available'));
    }

    return RefreshIndicator(
      onRefresh: _loadPosts,
      child: ListView.builder(
        itemCount: posts.length,
        padding: const EdgeInsets.all(16),
        itemBuilder: (context, index) {
          final post = posts[index];
          return PostCard(
            post: post,
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => PostDetailScreen(slug: post.slug),
                ),
              );
            },
          );
        },
      ),
    );
  }
}

// --------------------------------------------
// 6. POST CARD WIDGET
// File: lib/widgets/post_card.dart
// --------------------------------------------
import 'package:intl/intl.dart';

class PostCard extends StatelessWidget {
  final Post post;
  final VoidCallback onTap;

  const PostCard({
    super.key,
    required this.post,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: onTap,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            if (post.image != null)
              Image.network(
                post.image!,
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) {
                  return Container(
                    height: 200,
                    color: Colors.grey[300],
                    child: const Icon(Icons.image, size: 50),
                  );
                },
              ),

            // Content
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Title
                  Text(
                    post.title,
                    style: Theme.of(context).textTheme.titleLarge,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),

                  // Meta
                  Row(
                    children: [
                      Icon(Icons.person, size: 16, color: Colors.grey[600]),
                      const SizedBox(width: 4),
                      Text(
                        post.author,
                        style: TextStyle(color: Colors.grey[600]),
                      ),
                      const SizedBox(width: 16),
                      Icon(Icons.calendar_today, size: 16, color: Colors.grey[600]),
                      const SizedBox(width: 4),
                      Text(
                        DateFormat('dd MMM yyyy', 'id_ID').format(post.publishedAt),
                        style: TextStyle(color: Colors.grey[600]),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),

                  // Categories
                  if (post.categories.isNotEmpty)
                    Wrap(
                      spacing: 8,
                      children: post.categories.map((cat) {
                        return Chip(
                          label: Text(
                            cat.namaKategori,
                            style: const TextStyle(fontSize: 12),
                          ),
                          padding: EdgeInsets.zero,
                          materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
                        );
                      }).toList(),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// --------------------------------------------
// 7. POST DETAIL SCREEN
// File: lib/screens/post_detail_screen.dart
// --------------------------------------------
class PostDetailScreen extends StatefulWidget {
  final String slug;

  const PostDetailScreen({super.key, required this.slug});

  @override
  State<PostDetailScreen> createState() => _PostDetailScreenState();
}

class _PostDetailScreenState extends State<PostDetailScreen> {
  Post? post;
  bool isLoading = true;
  String? error;

  @override
  void initState() {
    super.initState();
    _loadPost();
  }

  Future<void> _loadPost() async {
    setState(() {
      isLoading = true;
      error = null;
    });

    try {
      final fetchedPost = await PostService.getPostBySlug(widget.slug);
      setState(() {
        post = fetchedPost;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        error = e.toString();
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Detail Berita'),
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (error != null) {
      return Center(child: Text('Error: $error'));
    }

    if (post == null) {
      return const Center(child: Text('Post not found'));
    }

    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image
          if (post!.image != null)
            Image.network(
              post!.image!,
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),

          // Content
          Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Title
                Text(
                  post!.title,
                  style: Theme.of(context).textTheme.headlineMedium,
                ),
                const SizedBox(height: 16),

                // Meta
                Row(
                  children: [
                    const Icon(Icons.person, size: 16),
                    const SizedBox(width: 4),
                    Text(post!.author),
                    const SizedBox(width: 16),
                    const Icon(Icons.calendar_today, size: 16),
                    const SizedBox(width: 4),
                    Text(
                      DateFormat('dd MMMM yyyy', 'id_ID').format(post!.publishedAt),
                    ),
                  ],
                ),
                const SizedBox(height: 16),

                // Categories
                if (post!.categories.isNotEmpty)
                  Wrap(
                    spacing: 8,
                    children: post!.categories.map((cat) {
                      return Chip(label: Text(cat.namaKategori));
                    }).toList(),
                  ),
                const SizedBox(height: 24),

                // Content
                Text(
                  post!.content,
                  style: Theme.of(context).textTheme.bodyLarge,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
