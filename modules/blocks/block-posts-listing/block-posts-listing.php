<?php
// ==============================
// Posts Filter — Categories only (with fade + span)
// ==============================
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'posts-filter'
  . (!empty($block['className']) ? ' ' . $block['className'] : '')
  . (!empty($block['align'])     ? ' align' . $block['align'] : '');

$filter_title = get_field('filter_title');

// Categories
$categories = get_terms([
  'taxonomy'   => 'category',
  'hide_empty' => true,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);

$cat_terms_data = array_map(function($cat) {
  return [
    'slug' => $cat->slug,
    'name' => html_entity_decode($cat->name),
  ];
}, $categories);

if (is_admin()) {
  echo '<p><strong>Posts Filter (Categories only)</strong></p>';
  return;
}
?>

<!-- Safely hand off config -->
<script>
window.__PF = window.__PF || {};
window.__PF[<?= json_encode($id) ?>] = {
  catTerms: <?= wp_json_encode($cat_terms_data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) ?>,
  ajaxUrl: <?= json_encode(admin_url('admin-ajax.php')) ?>,
  totalCount: <?= (int) (wp_count_posts()->publish ?? 0); ?>
};
</script>

<div id="<?= esc_attr($id) ?>" class="<?= esc_attr($className) ?>"
     x-data="postsApp('<?= esc_js($id) ?>')" x-init="init()" x-cloak>

  <?php if (!empty($filter_title)) : ?>
    <h2 class="posts-filter-title is-style-highlight-words"><?= esc_html($filter_title); ?></h2>
  <?php endif; ?>

  <!-- Category buttons -->
  <div class="category-buttons" role="tablist" aria-label="Filter by category">
    <button type="button" class="cat-btn"
            :class="{ 'active': !filter.category }"
            @click="selectCategory('')">
      <span>All posts</span>
    </button>

    <template x-for="term in catTerms" :key="term.slug">
      <button type="button" class="cat-btn"
              :class="{ 'active': filter.category === term.slug }"
              @click="selectCategory(term.slug)">
        <span x-text="term.name"></span>
      </button>
    </template>
  </div>

  <!-- Posts with fade -->
  <div class="posts-listing" :class="{ 'is-switching': switching }">
    <template x-for="post in posts" :key="post.id">
      <div class="post-item" x-html="post.content"></div>
    </template>
  </div>

  <div x-show="noResults && !loading" class="no-results"><p>Sorry, nothing found.</p></div>

  <div class="load-more-posts">
    <button class="btn load-more" x-show="!allLoaded"
            @click="loadMore()"
            x-text="loading ? 'Loading...' : 'Load more'"></button>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('postsApp', (key) => ({
    // Config
    get cfg()     { return (window.__PF && window.__PF[key]) || {}; },
    get catTerms(){ return this.cfg.catTerms || []; },
    get ajaxUrl() { return this.cfg.ajaxUrl; },

    // State
    posts: [],
    offset: 0,
    postsPerPage: 12,
    loading: false,
    filter: { category: '' },
    noResults: false,
    allLoaded: false,
    switching: false, // fade animation state

    init() {
      // Optional: read category from URL
      const params = new URLSearchParams(window.location.search);
      const catParam = params.get('category');
      if (catParam) this.filter.category = catParam;

      // Fade before first load
      this.switching = true;
      this.loadPosts(true, 12).then(() => {
        requestAnimationFrame(() => { this.switching = false; });
      });
    },

    selectCategory(val) {
      this.filter.category = val || '';
      this.offset = 0;
      this.allLoaded = false;

      this.switching = true; // fade-out before load
      this.loadPosts(true, 12).then(() => {
        requestAnimationFrame(() => { this.switching = false; }); // fade-in
      });
      this.updateUrl();
    },

    updateUrl() {
      const url = new URL(window.location.href);
      if (this.filter.category) url.searchParams.set('category', this.filter.category);
      else url.searchParams.delete('category');
      history.pushState(null, '', url);
    },

    loadPosts(reset = false, customLimit = null) {
      if (this.loading) return Promise.resolve();
      this.loading = true;

      const fd = new FormData();
      fd.append('action', 'load_wp_posts');
      fd.append('offset', reset ? 0 : this.offset);
      fd.append('posts_per_page', customLimit ?? this.postsPerPage);
      fd.append('category', this.filter.category);

      return fetch(this.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: fd })
        .then(r => r.json())
        .then(data => {
          const newPosts = data.posts || [];
          if (reset) {
            this.posts = newPosts;
            this.offset = newPosts.length;
          } else {
            this.posts = this.posts.concat(newPosts);
            this.offset += newPosts.length;
          }

          this.noResults = this.posts.length === 0;
          if (newPosts.length < (customLimit ?? this.postsPerPage)) this.allLoaded = true;
          this.loading = false;

          this.$nextTick(() => {
            if (typeof GLightbox !== 'undefined') GLightbox({ selector: '.glightbox' });
            if (typeof equalizeHeights === 'function') setTimeout(equalizeHeights, 50);
          });
        })
        .catch(err => {
          console.error(err);
          this.loading = false;
        });
    },

    loadMore() {
      this.loadPosts();
    }
  }));
});
</script>