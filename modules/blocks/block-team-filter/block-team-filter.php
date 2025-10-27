<?php
$id             = $block['anchor'] ?? 'team-block-' . $block['id'];
$parent_term_id = get_field('team_category') ?: ''; // Parent term (e.g., 'Team')

// Build className
$className = 'team'
	. (!empty($block['className']) ? " {$block['className']}" : '')
	. (!empty($block['align']) ? " align{$block['align']}" : '');

// Get the child terms of the selected parent
$children = [];
if ($parent_term_id) {
	$child_terms = get_terms([
		'taxonomy'   => 'team-category',
		'hide_empty' => true,
		'parent'     => (int) $parent_term_id,
		'orderby'    => 'name', // change to 'term_order' if you use a term-order plugin
		'order'      => 'ASC',
	]);

	if (!is_wp_error($child_terms) && $child_terms) {
		foreach ($child_terms as $t) {
			$children[] = [
				'id'   => (int) $t->term_id,
				'name' => $t->name,
				'slug' => $t->slug,
			];
		}
	}
}

if (is_admin()) {
	echo '<p><strong>Team Members</strong> – interactive on front end.</p>';
	return;
}
?>

<div
	id="<?= esc_attr($id) ?>"
	class="<?= esc_attr($className) ?>"
	x-data='teamApp({
		children: <?= wp_json_encode($children, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		ajaxUrl: <?= wp_json_encode(admin_url('admin-ajax.php')) ?>
	})'
	x-init="init()"
	x-cloak
>
	<!-- Child-term buttons (no "All") -->
	<div class="team-controls" x-show="children.length">
	  <template x-for="t in children" :key="t.id">
		<button
		  type="button"
		  class="team-filter-btn"
		  :class="{ 'active': category === t.id }"
		  @click="selectCategory(t.id)"
		>
		  <span class="btn-label" x-text="t.name"></span>
		</button>
	  </template>
	</div>
	
	<div x-show="loading" class="loading">Loading…</div>
	<div x-show="!loading && displayed.length === 0" class="no-results">Sorry, nothing found.</div>

	<!-- Results list with fade -->
	<div class="team-list" :class="{ 'is-switching': switching }">
		<template x-for="(member, i) in displayed" :key="member.id">
			<div class="team__member" x-html="member.html" @click="openModal(i)"></div>
		</template>
	</div>

	<!-- Modal -->
	<div id="team__modal" class="modal" x-ref="modal" @keydown.escape.window="closeModal()" @keydown.window="handleKeyNavigation">
		<div class="modal-inner">
			<div class="modal-controls">
				<button id="close" class="modal-close" @click="closeModal()">
					<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none"><path fill="#FF6139" d="M21 41.996A20.896 20.896 0 0 1 7.328 36.93 21.003 21.003 0 0 1 .571 25.862c-.13-.553-.247-1.11-.331-1.678a20.342 20.342 0 0 1-.18-1.678C.025 22.008 0 21.508 0 21 0 15.2 2.352 9.954 6.154 6.156A20.975 20.975 0 0 1 21 0c2.701 0 5.173.494 7.504 1.386.572.218 1.132.458 1.679.726C37.179 5.518 42 12.697 42 21c0 11.596-9.405 21-21.003 21l.003-.004Z"/><path stroke="#fff" stroke-linecap="round" stroke-width="1.6" d="M27.46 14.533 14.534 27.461M27.467 27.461 14.539 14.534"/></svg>
				</button>
				<div class="modal-nav">
					<button class="next" :disabled="modalIndex===displayed.length-1" @click="next()">
						<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none"><path stroke="#FF6139" d="M21 41.496h.299C32.482 41.334 41.5 32.22 41.5 21c0-8.105-4.705-15.113-11.536-18.438l-.001-.001a19.76 19.76 0 0 0-1.638-.708A20.222 20.222 0 0 0 21 .5 20.475 20.475 0 0 0 6.884 6.143l-.377.367A20.412 20.412 0 0 0 .5 21c0 .492.024.98.059 1.47v.002c.038.553.093 1.1.175 1.636v.002c.041.275.09.547.145.82l.179.817a20.504 20.504 0 0 0 6.595 10.804h.001A20.395 20.395 0 0 0 21 41.496Z"/><path stroke="#FF6139" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m26.993 26.157 5.158-5.098-5.158-5.218"/><path stroke="#FF6139" stroke-linecap="round" stroke-width="1.6" d="M31.352 20.784H10.649"/></svg>
					</button>
					<button class="prev" :disabled="modalIndex===0" @click="prev()">
						<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="none"><path stroke="#FF6139" d="M21 41.496h.299C32.482 41.334 41.5 32.22 41.5 21c0-8.105-4.705-15.113-11.536-18.438l-.001-.001a19.76 19.76 0 0 0-1.638-.708A20.222 20.222 0 0 0 21 .5 20.475 20.475 0 0 0 6.884 6.143l-.377.367A20.412 20.412 0 0 0 .5 21c0 .492.024.98.059 1.47v.002c.038.553.093 1.1.175 1.636v.002c.041.275.09.547.145.82l.179.817a20.504 20.504 0 0 0 6.595 10.804h.001A20.395 20.395 0 0 0 21 41.496Z"/><path stroke="#FF6139" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m16.008 26.158-5.158-5.099 5.158-5.217"/><path stroke="#FF6139" stroke-linecap="round" stroke-width="1.6" d="M11.649 20.785h20.703"/></svg>
					</button>
				</div>
			</div>
			<div class="modal-content">
				<img id="team-modal-img" src="" alt="" />
				<div id="title"><h3></h3><p></p></div>
				<div id="bio"></div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('teamApp', (initData) => ({
	// Input
	children: initData.children || [],
	ajaxUrl: initData.ajaxUrl,

	// State
	category: null,        // active child term id
	allMembers: [],
	displayed: [],
	loading: false,
	switching: false,      // <— fade state

	// Modal
	modalIndex: 0,

	init() {
	  // Preselect the first child term (if any)
	  if (this.children.length > 0) {
		this.category = this.children[0].id; // First child preselected
		this.switching = true;               // fade-out before first load
		this.fetchMembers().then(() => {
		  requestAnimationFrame(() => { this.switching = false; }); // fade-in
		});
	  }
	},

	selectCategory(id) {
	  if (this.category === id) return;
	  this.switching = true;    // start fade-out
	  this.category = id;
	  this.fetchMembers().then(() => {
		requestAnimationFrame(() => { this.switching = false; }); // fade-in
	  });
	},

	fetchMembers() {
	  this.loading = true;

	  const fd = new FormData();
	  fd.append('action', 'load_team_members');
	  fd.append('category', this.category || '');

	  return fetch(this.ajaxUrl, {
		method: 'POST',
		credentials: 'same-origin',
		body: fd
	  })
	  .then(r => r.json())
	  .then(json => {
		this.allMembers = json.members || [];
		this.displayed = this.allMembers;   // show exactly what comes back
		this.loading = false;
	  })
	  .catch(() => { this.loading = false; });
	},

	// ===== Modal methods =====
	openModal(i) {
	  this.modalIndex = i;
	  this.populateModal();
	},
	populateModal() {
	  const m = this.displayed[this.modalIndex];
	  if (!m) return;

	  const tmp = document.createElement('div');
	  tmp.innerHTML = m.html;

	  document.querySelector('#team__modal #title h3').innerText = tmp.querySelector('.title h3')?.innerText || '';
	  document.querySelector('#team__modal #title p').innerText = tmp.querySelector('.title p')?.innerText || '';
	  document.querySelector('#team__modal #team-modal-img').src = tmp.querySelector('.profile img')?.src || '';
	  document.querySelector('#team__modal #bio').innerHTML = tmp.querySelector('.bio')?.innerHTML || '';

	  requestAnimationFrame(() => {
		document.getElementById('team__modal')?.classList.add('active');
		document.documentElement.classList.add('menu-opened');
	  });
	},
	handleKeyNavigation(e) {
	  if (!this.$refs.modal.classList.contains('active')) return;
	  if (e.key === 'ArrowRight') this.next();
	  if (e.key === 'ArrowLeft') this.prev();
	},
	closeModal() {
	  this.$refs.modal.classList.remove('active');
	  document.documentElement.classList.remove('menu-opened');
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
</script>