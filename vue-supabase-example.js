// ============================================
// VUE.JS + SUPABASE INTEGRATION
// Portal Berita - Example Implementation
// ============================================

// --------------------------------------------
// 1. SETUP SUPABASE CLIENT
// File: src/supabase.js
// --------------------------------------------
import { createClient } from '@supabase/supabase-js'

const supabaseUrl = 'https://alpivotfqqqsjhdovlrj.supabase.co'
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFscGl2b3RmcXFxc2poZG92bHJqIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA2MDgxNzksImV4cCI6MjA4NjE4NDE3OX0.tnbqAkJSHJ1IF597D2PpfY3X2DmBFXZbX_frW5qZpck'

export const supabase = createClient(supabaseUrl, supabaseAnonKey, {
  db: {
    schema: 'portal_berita'  // ✅ Custom schema
  }
})

// --------------------------------------------
// 2. COMPOSABLE (Vue 3 Composition API)
// File: src/composables/usePosts.js
// --------------------------------------------
import { ref } from 'vue'
import { supabase } from '@/supabase'

export function usePosts() {
  const posts = ref([])
  const post = ref(null)
  const loading = ref(false)
  const error = ref(null)

  // Fetch all posts
  const fetchPosts = async () => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await supabase
        .from('posts')
        .select(`
          *,
          categories (
            id,
            nama_kategori
          )
        `)
        .order('published_at', { ascending: false })

      if (fetchError) throw fetchError

      posts.value = data
    } catch (err) {
      error.value = err.message
      console.error('Error fetching posts:', err)
    } finally {
      loading.value = false
    }
  }

  // Fetch single post by slug
  const fetchPostBySlug = async (slug) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await supabase
        .from('posts')
        .select(`
          *,
          categories (
            id,
            nama_kategori
          )
        `)
        .eq('slug', slug)
        .single()

      if (fetchError) throw fetchError

      post.value = data
    } catch (err) {
      error.value = err.message
      console.error('Error fetching post:', err)
    } finally {
      loading.value = false
    }
  }

  // Fetch posts by category
  const fetchPostsByCategory = async (categoryId) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await supabase
        .from('posts')
        .select(`
          *,
          categories!inner (
            id,
            nama_kategori
          )
        `)
        .eq('categories.id', categoryId)
        .order('published_at', { ascending: false })

      if (fetchError) throw fetchError

      posts.value = data
    } catch (err) {
      error.value = err.message
      console.error('Error fetching posts by category:', err)
    } finally {
      loading.value = false
    }
  }

  // Search posts
  const searchPosts = async (query) => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await supabase
        .from('posts')
        .select('*')
        .or(`title.ilike.%${query}%,content.ilike.%${query}%`)
        .order('published_at', { ascending: false })

      if (fetchError) throw fetchError

      posts.value = data
    } catch (err) {
      error.value = err.message
      console.error('Error searching posts:', err)
    } finally {
      loading.value = false
    }
  }

  return {
    posts,
    post,
    loading,
    error,
    fetchPosts,
    fetchPostBySlug,
    fetchPostsByCategory,
    searchPosts
  }
}

// --------------------------------------------
// 3. VUE COMPONENT EXAMPLE
// File: src/views/PostsView.vue
// --------------------------------------------
/*
<template>
  <div class="posts-container">
    <h1>Berita Terbaru</h1>

    <div v-if="loading" class="loading">
      Loading...
    </div>

    <div v-else-if="error" class="error">
      Error: {{ error }}
    </div>

    <div v-else class="posts-grid">
      <article 
        v-for="post in posts" 
        :key="post.id"
        class="post-card"
      >
        <img :src="post.image" :alt="post.title" />
        <h2>{{ post.title }}</h2>
        <p>{{ post.author }} • {{ formatDate(post.published_at) }}</p>
        <div class="categories">
          <span 
            v-for="cat in post.categories" 
            :key="cat.id"
            class="category-badge"
          >
            {{ cat.nama_kategori }}
          </span>
        </div>
        <router-link :to="`/posts/${post.slug}`">
          Baca Selengkapnya
        </router-link>
      </article>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { usePosts } from '@/composables/usePosts'

const { posts, loading, error, fetchPosts } = usePosts()

onMounted(() => {
  fetchPosts()
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>
*/

// --------------------------------------------
// 4. SINGLE POST VIEW EXAMPLE
// File: src/views/PostDetailView.vue
// --------------------------------------------
/*
<script setup>
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { usePosts } from '@/composables/usePosts'

const route = useRoute()
const { post, loading, error, fetchPostBySlug } = usePosts()

onMounted(() => {
  fetchPostBySlug(route.params.slug)
})
</script>

<template>
  <article v-if="!loading && post" class="post-detail">
    <h1>{{ post.title }}</h1>
    <div class="meta">
      <span>{{ post.author }}</span>
      <span>{{ new Date(post.published_at).toLocaleDateString('id-ID') }}</span>
    </div>
    <img :src="post.image" :alt="post.title" />
    <div class="content" v-html="post.content"></div>
    <div class="categories">
      <span v-for="cat in post.categories" :key="cat.id">
        {{ cat.nama_kategori }}
      </span>
    </div>
  </article>
</template>
*/

// --------------------------------------------
// 5. REALTIME SUBSCRIPTION (Optional)
// File: src/composables/useRealtimePosts.js
// --------------------------------------------
import { ref, onUnmounted } from 'vue'
import { supabase } from '@/supabase'

export function useRealtimePosts() {
  const posts = ref([])
  let subscription = null

  const subscribeToNewPosts = () => {
    subscription = supabase
      .channel('posts-channel')
      .on(
        'postgres_changes',
        {
          event: 'INSERT',
          schema: 'portal_berita',
          table: 'posts'
        },
        (payload) => {
          console.log('New post:', payload.new)
          posts.value.unshift(payload.new)
        }
      )
      .subscribe()
  }

  const unsubscribe = () => {
    if (subscription) {
      supabase.removeChannel(subscription)
    }
  }

  onUnmounted(() => {
    unsubscribe()
  })

  return {
    posts,
    subscribeToNewPosts,
    unsubscribe
  }
}

// --------------------------------------------
// 6. CATEGORIES COMPOSABLE
// File: src/composables/useCategories.js
// --------------------------------------------
export function useCategories() {
  const categories = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchCategories = async () => {
    loading.value = true
    error.value = null

    try {
      const { data, error: fetchError } = await supabase
        .from('categories')
        .select('*')
        .order('nama_kategori')

      if (fetchError) throw fetchError

      categories.value = data
    } catch (err) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  return {
    categories,
    loading,
    error,
    fetchCategories
  }
}
