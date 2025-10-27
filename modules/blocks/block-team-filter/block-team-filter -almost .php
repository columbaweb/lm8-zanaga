<?php
// ===============================================
// Block Template: Team Members + GSAP once + clearProps + autoAlpha restore
// ===============================================

$id            = $block['anchor'] ?? 'team-block-' . $block['id'];
$team_category = get_field('team_category') ?: '';
$category_slug = '';

if ( $team_category ) {
    $term = get_term( $team_category, 'team-category' );
    if ( ! is_wp_error( $term ) ) {
        $category_slug = ' team-cat-' . sanitize_title( $term->slug );
    }
}

$className = 'team'
  . (! empty($block['className']) ? " {$block['className']}" : '')
  . (! empty($block['align'])     ? " align{$block['align']}"   : '')
  . $category_slug;

if ( is_admin() ) {
  echo '<p><strong>Team Members</strong> – interactive on front end.</p>';
  return;
}
?>

<div
  id="<?= esc_attr($id) ?>"
  class="<?= esc_attr($className) ?>"
  x-data="teamApp({ category: <?= json_encode($team_category) ?> })"
  x-init="init()"
  x-cloak
>
  <!-- Controls -->
  <div class="team-controls">
    <input
      type="text"
      class="search-bar"
      x-model="searchQuery"
      placeholder="Search team members…"
      autocomplete="off"
    />
    <select class="sort-dropdown" x-model="sortOption">
      <option value="default">Default</option>
      <option value="first_name">First Name</option>
      <option value="last_name">Last Name</option>
    </select>
    <button class="btn reset-btn" @click="resetFilters()">Reset</button>
  </div>

  <!-- Listing -->
  <div class="team-list">
    <template x-for="(member, i) in displayed" :key="member.id">
      <div
        class="team__member"
        x-html="member.html"
        @click="openModal(i)"
      ></div>
    </template>
  </div>

  <!-- Loading / No Results -->
  <div x-show="loading" class="loading">Loading…</div>
  <div x-show="!loading && displayed.length === 0" class="no-results">
    Sorry, nothing found.
  </div>

  <!-- Modal -->
  <div
    id="team__modal"
    class="modal"
    x-ref="modal"
    @click.away="closeModal()"
    @keydown.escape.window="closeModal()"
  >
    <div class="modal-inner">
      <button id="close" class="modal-close" @click="closeModal()">×</button>
      <div id="title"><h3></h3><p></p></div>
      <img id="team-modal-img" src="" alt="" />
      <div id="bio"></div>
      <div class="modal-nav">
        <button class="prev" x-bind:disabled="modalIndex===0" @click="prev()">Prev</button>
        <button class="next"
                x-bind:disabled="modalIndex===displayed.length-1"
                @click="next()"
        >Next</button>
      </div>
    </div>
  </div>
</div>

<script>
// -------------------------------------
// 1) GSAP Fade-Up (initial load only)
// -------------------------------------
function initGsap() {
  if (typeof gsap==='undefined' || typeof ScrollTrigger==='undefined') return;
  gsap.registerPlugin(ScrollTrigger);
  gsap.fromTo(
    ".team__member",
    { y: 50, autoAlpha: 0 },
    {
      y: 0,
      autoAlpha: 1,
      duration: 1,
      ease: "power2.out",
      stagger: 0.1,
      scrollTrigger: {
        trigger: ".team",
        start: "top 80%",
        end: "bottom 0%",
        scrub: false,
      }
    }
  );
}

// -------------------------------------
// 2) Alpine Component
// -------------------------------------
document.addEventListener('alpine:init', () => {
  Alpine.data('teamApp', () => ({
    category: null,
    allMembers: [],
    displayed: [],
    searchQuery: '',
    sortOption: 'default',
    loading: false,
    modalIndex: 0,
    debounceTimer: null,

    init() {
      this.category = <?= json_encode($team_category) ?>;
      this.fetchMembers();
      this.$watch('searchQuery', () => {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.applyFilters(), 200);
      });
      this.$watch('sortOption', () => this.applyFilters());
    },

    fetchMembers() {
      this.loading = true;
      const fd = new FormData();
      fd.append('action',   'load_team_members');
      fd.append('category', this.category);

      fetch("<?= admin_url('admin-ajax.php') ?>", {
        method: 'POST',
        credentials: 'same-origin',
        body: fd
      })
      .then(r => r.json())
      .then(json => {
        this.allMembers = json.members;
        this.applyFilters();
        this.loading = false;
        this.$nextTick(initGsap);
      })
      .catch(() => { this.loading = false; });
    },

    applyFilters() {
      let items = [...this.allMembers];
      const q = this.searchQuery.trim().toLowerCase();

      if (q) {
        items = items.filter(i =>
          i.title.toLowerCase().includes(q) ||
          i.html.toLowerCase().includes(q)
        );
      }
      if (this.sortOption === 'first_name') {
        items.sort((a,b) => a.first_name.localeCompare(b.first_name));
      } else if (this.sortOption === 'last_name') {
        items.sort((a,b) => a.last_name.localeCompare(b.last_name));
      }

      this.displayed = items;

      // clear any GSAP inline props then force all visible
      this.$nextTick(() => {
        if (window.gsap) {
          gsap.set(".team__member", { clearProps: "all", autoAlpha: 1 });
        }
      });
    },

    resetFilters() {
      this.searchQuery = '';
      this.sortOption  = 'default';
      this.applyFilters();
    },

    openModal(i) {
      this.modalIndex = i;
      this.populateModal();
    },

    populateModal() {
      const m = this.displayed[this.modalIndex];
      if (!m) return;
      const tmp = document.createElement('div');
      tmp.innerHTML = m.html;
      const t   = tmp.querySelector('.title h3')?.innerText || '';
      const p   = tmp.querySelector('.title p')?.innerText  || '';
      const src = tmp.querySelector('.profile img')?.src    || '';
      const bio = tmp.querySelector('.bio')?.innerHTML      || '';

      document.querySelector('#team__modal #title h3').innerText     = t;
      document.querySelector('#team__modal #title p').innerText      = p;
      document.querySelector('#team__modal #team-modal-img').src     = src;
      document.querySelector('#team__modal #bio').innerHTML          = bio;
    },

    prev() {
      if (this.modalIndex > 0) {
        this.modalIndex--;
        this.populateModal();
      }
    },

    next() {
      if (this.modalIndex < this.displayed.length - 1) {
        this.modalIndex++;
        this.populateModal();
      }
    }
  }));
});

// -------------------------------------
// 3) Vanilla JS: Modal toggles + keyboard
// -------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('team__modal');
  const list  = document.querySelector('.team-list');
  if (!modal || !list) return;

  list.addEventListener('click', e => {
    if (e.target.closest('.team__member')) {
      modal.classList.add('active');
      document.documentElement.classList.add('menu-opened');
    }
  });

  modal.addEventListener('click', e => {
    if (e.target.id==='team__modal' || e.target.id==='close') {
      modal.classList.remove('active');
      document.documentElement.classList.remove('menu-opened');
    }
  });

  document.addEventListener('keydown', e => {
    if (!modal.classList.contains('active')) return;
    if (e.key==='Escape') {
      modal.classList.remove('active');
      document.documentElement.classList.remove('menu-opened');
    }
    if (e.key==='ArrowLeft') {
      const btn = modal.querySelector('.modal-nav .prev:not([disabled])');
      btn && btn.click();
    }
    if (e.key==='ArrowRight') {
      const btn = modal.querySelector('.modal-nav .next:not([disabled])');
      btn && btn.click();
    }
  });
});
</script>