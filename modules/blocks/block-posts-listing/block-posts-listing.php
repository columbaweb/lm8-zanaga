<?php

// ===============================================
// Block Template: Posts Filter by Category + post_sector
// with ACF toggles
// ===============================================

// Set block ID and classes.
$id = $block['anchor'] ?? 'lmn-' . $block['id'];
$className = 'posts-filter'
  . (!empty($block['className']) ? ' ' . $block['className'] : '')
  . (!empty($block['align'])     ? ' align' . $block['align'] : '');

// ACF toggles (true/false)
$show_filter  = (bool) get_field('filters');
$show_title = (bool) get_field('title');
$show_search  = (bool) get_field('search');
$show_cat     = (bool) get_field('category_dd');
$show_tags   = (bool) get_field('tags_dd');
$show_year   = (bool) get_field('year_dd');

// Get terms
$categories = get_terms([
	'taxonomy'   => 'category',
	'hide_empty' => true,
]);

$cat_terms_data = array_map(function($cat) {
return [
	'slug' => $cat->slug,
	'name' => html_entity_decode($cat->name),
];
}, $categories);


$tags = get_tags(['hide_empty' => true]);
$tag_terms_data = array_map(fn($t) => ['slug' => $t->slug, 'name' => html_entity_decode($t->name)], $tags);

// Get years from posts
global $wpdb;
$years = $wpdb->get_col("
	SELECT DISTINCT YEAR(post_date) 
	FROM {$wpdb->posts} 
	WHERE post_type = 'post' 
	  AND post_status = 'publish' 
	ORDER BY post_date DESC
");
$year_terms_data = array_map(fn($y) => ['slug' => $y, 'name' => (string) $y], $years);


if ( is_admin() ) {
	echo '<p><strong>Posts Filter Block</strong></p>';
	return;
  }
  ?>
  
    <div class="<?= esc_attr($className) ?>"
	x-data="postsApp({
	  catTerms: <?= htmlspecialchars(json_encode($cat_terms_data)) ?>,
	  tagTerms: <?= htmlspecialchars(json_encode($tag_terms_data)) ?>,
	  yearTerms: <?= htmlspecialchars(json_encode($year_terms_data)) ?>,
	  totalCount: <?= wp_count_posts()->publish; ?>
	})"
	x-init="init()"
	x-cloak
    >
  
        <?php if ( $show_title ): ?>
        <h2 class="posts-filter-title is-style-highlight-words">
            All media 
            <strong class="total-count" x-text="totalCount"></strong>
        </h2>
        <?php endif; ?>
            
        <?php if ( $show_filter ): ?>
        <div class="posts-filter-controls">

            <div class="inner">
                <span class="posts-filter-label">
                    Filter by
                </span>
        
                <?php if ( $show_cat ): ?>
                <div class="dropdown">
                    <button @click="toggleDropdown('category')" type="button" class="dropdown-toggle">
                    <span x-text="dropdownText('category')"></span>
                    </button>
                    <ul x-show="dropdownOpen.category" @click.away="dropdownOpen.category = false" x-transition class="dropdown-menu">			  
                        <li @click="selectFilter('category', '')" class="dropdown-item">View all</li>
                    <template x-for="term in catTerms" :key="term.slug">
                        <li @click="selectFilter('category', term.slug)" class="dropdown-item" x-text="term.name"></li>
                    </template>
                    </ul>
                </div>
                <?php endif; ?>
    
                <?php if ( $show_tags ): ?>
                <div class="dropdown">
                    <button @click="toggleDropdown('tag')" type="button" class="dropdown-toggle">
                    <span x-text="dropdownText('tag')"></span>
                    </button>
                    <ul x-show="dropdownOpen.tag" @click.away="dropdownOpen.tag = false" x-transition class="dropdown-menu">
                    <li @click="selectFilter('tag', '')" class="dropdown-item">View all</li>
                    <template x-for="term in tagTerms" :key="term.slug">
                        <li @click="selectFilter('tag', term.slug)" class="dropdown-item" x-text="term.name"></li>
                    </template>
                    </ul>
                </div>
                <?php endif; ?>
    
                <?php if ( $show_year ): ?>
                <div class="dropdown">
                    <button @click="toggleDropdown('year')" type="button" class="dropdown-toggle">
                    <span x-text="dropdownText('year')"></span>
                    </button>
                    <ul x-show="dropdownOpen.year" @click.away="dropdownOpen.year = false" x-transition class="dropdown-menu">
                    <li @click="selectFilter('year', '')" class="dropdown-item">View all</li>
                    <template x-for="term in yearTerms" :key="term.slug">
                        <li @click="selectFilter('year', term.slug)" class="dropdown-item" x-text="term.name"></li>
                    </template>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if ( $show_search ): ?>
                <div class="search-bar">
                    <input type="text" x-model="searchQuery" placeholder="Search..." autocomplete="off" />
                </div>
                <?php endif; ?>

                <?php 
    //show reset if any control is visible
                if ( $show_search || $show_year || $show_tags || $show_cat ): 
                ?>
                <button type="button" class="filter-reset" @click="resetFilter()">Reset</button>
            <?php endif; ?>
        
                </div>
        </div>
        <?php endif; ?>
    
        <div class="posts-listing">
        <template x-for="post in posts" :key="post.id">
            <div class="post-item" x-html="post.content"></div>
        </template>
        </div>
    
        <div x-show="noResults && !loading" class="no-results">
            <p>Sorry, nothing found.</p>
        </div>
	
        <div class="load-more-posts">
            <button class="btn load-more" x-show="!allLoaded" @click="loadMore()" x-text="loading ? 'Loading...' : 'Load more'"></button>
        </div>
    </div>
  
  <script>
    //Equalize Heights Functions
    (function() {
    const classBreakpoints = [
        //{ className: "e1", breakpoint: 782 },
        { className: "equal", breakpoint: 680 }
    ];

    function equalizeRowHeights(className, breakpoint) {
        var rows = document.getElementsByClassName(className);
        var maxHeight = 0;

        // For small screens, reset heights.
        if (window.innerWidth < breakpoint) {
        for (var i = 0; i < rows.length; i++) {
            rows[i].style.height = "auto";
        }
        return;
        }

        // Reset then determine maximum height.
        for (var i = 0; i < rows.length; i++) {
        rows[i].style.height = "auto";
        maxHeight = Math.max(maxHeight, rows[i].clientHeight);
        }
        // Set all rows to maximum height.
        for (var i = 0; i < rows.length; i++) {
        rows[i].style.height = maxHeight + "px";
        }
    }

    function equalizeHeights() {
        classBreakpoints.forEach(bp => {
        equalizeRowHeights(bp.className, bp.breakpoint);
        });
    }

    // Expose equalizeHeights globally so your Alpine component can call it.
    window.equalizeHeights = equalizeHeights;
    })();

// alpine component	    
    function postsApp(data = {}) {
    return {
        posts: [],
        offset: 0,
        postsPerPage: 12,
        loading: false,
        filter: { category: '', tag: '', year: '' },
        searchQuery: '',
        noResults: false,
        allLoaded: false,
        dropdownOpen: { category: false, tag: false, year: false },
        catTerms: data.catTerms || [],
        tagTerms: data.tagTerms || [],
        yearTerms: data.yearTerms || [],
        totalCount: data.totalCount || 0,
        debounceTimeout: null,

        dropdownText(type) {
        const map = {
            category: { label: 'All topics', prefix: '', terms: this.catTerms },
            tag:      { label: 'All tags',    prefix: '',   terms: this.tagTerms },
            year:     { label: 'All years',   prefix: '',  terms: this.yearTerms },
        };
        const config = map[type];
        if (!config) return '';
        const val = this.filter[type];
        if (!val) return config.label;
        const match = config.terms.find(t => t.slug === val);
        return match ? `${match.name}` : config.label;
        },

        toggleDropdown(type) {
        this.dropdownOpen[type] = !this.dropdownOpen[type];
        },

        selectFilter(type, val) {
        if (!val) return this.resetFilter();
        this.filter[type] = val;
        this.dropdownOpen[type] = false;
        this.offset = 0;
        this.allLoaded = false;
        this.loadPosts(true, 12);
        this.updateUrl();
        },

        resetFilter() {
        this.filter = { category: '', tag: '', year: '' };
        this.searchQuery = '';
        Object.keys(this.dropdownOpen).forEach(k => this.dropdownOpen[k] = false);
        this.offset = 0;
        this.allLoaded = false;
        this.loadPosts(true, 12);
        this.updateUrl();
        },

        updateUrl() {
        const url = new URL(window.location.href);
        ['category', 'tag', 'year'].forEach(k => {
            if (this.filter[k]) url.searchParams.set(k, this.filter[k]);
            else url.searchParams.delete(k);
        });
        history.pushState(null, '', url);
        },

        init() {
        const params = new URLSearchParams(window.location.search);
        ['category', 'tag', 'year'].forEach(k => {
            if (params.get(k)) this.filter[k] = params.get(k);
        });

        this.loadPosts(true, 12);

        this.$watch('searchQuery', val => {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
            this.offset = 0;
            this.allLoaded = false;
            this.loadPosts(true, 12);
            }, 500);
        });
        },

        loadPosts(reset = false, customLimit = null) {
        if (this.loading) return;
        this.loading = true;
        const fd = new FormData();
        fd.append('action', 'load_wp_posts');
        fd.append('offset', reset ? 0 : this.offset);
        fd.append('posts_per_page', customLimit ?? this.postsPerPage);
        fd.append('category', this.filter.category);
        fd.append('tag', this.filter.tag);
        fd.append('year', this.filter.year);
        fd.append('search', this.searchQuery);

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: fd
        })
        .then(r => r.json())
        
        .then(data => {
            if (reset) {
                this.posts = data.posts;
                this.offset = data.posts.length;
            } else {
                this.posts = this.posts.concat(data.posts);
                this.offset += data.posts.length;
            }

            this.totalCount = data.total ?? this.totalCount;
            this.noResults = this.posts.length === 0;

            if (data.posts.length < (customLimit ?? this.postsPerPage)) {
                this.allLoaded = true;
            }

            this.loading = false;

            this.$nextTick(() => {
                if (typeof GLightbox !== 'undefined') {
                GLightbox({ selector: '.glightbox' });
                }
                if (typeof equalizeHeights === 'function') {
                setTimeout(equalizeHeights, 50);
                }
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
    }
    }
</script>