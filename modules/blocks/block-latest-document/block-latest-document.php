<?php
	$id = !empty($block['anchor']) ? $block['anchor'] : 'latest-documents-' . $block['id'];
	$className = 'file';

	if (!empty($block['className'])) { $className .= ' ' . $block['className']; }
	if (!empty($block['align'])) { $className .= ' align' . $block['align']; }

	$type = get_field('type');
	$style = get_field('title_display');
	$className .= $style ? ' ' . $style : '';

	$file = '';
	$target = '_self';
	if (get_field('file')) {
		$file = get_field('file');
		$target = '_blank';
	} elseif (get_field('url')) {
		$file = get_field('url');
		$target = '_blank';
	}

	$cover = get_field('cover');
	$title = get_field('title');
	$date = get_field('date');
	$sub = get_field('subtitle');
?>

<div class="latest-document">
    <?php if ($type === 'single') { ?>
        <a class="<?= esc_attr($className); ?>" href="<?= esc_url($file); ?>" target="<?= esc_attr($target); ?>">
            <span class="cover" style="background-image: url('<?= esc_url($cover); ?>');"></span>
            
			<div class="inner">
				<?php if ($title) { ?>
					<h3><?= $title; ?></h3>
				<?php } ?>
				
				<div class="title">
					<?php if ($date) { ?>
						<span class="date"><?= $date; ?></span>
					<?php } ?> 
					
					<?php if ($sub) { ?>
						<span class="name e1"><?= $sub; ?></span>
					<?php } ?> 
					<?= '<span class="btn">'.file_get_contents(get_template_directory() . '/assets/images/theme/icon-plus.svg').'</span>'; ?>
				</div>
			</div>
        </a>
    <?php } else { ?> 
		
		<?php 
			$cat = get_field('latest_doc_category');

			$args = array(
				'post_type' => 'documents',
				'posts_per_page' => 1,
				'order' => 'DESC',
	
				'tax_query' => array(
					array(
					  'taxonomy' => 'document-type',
					  'field' => 'term_id',
					  'terms' => $cat
					),
				  )
	
			);
			$fileQuery = new WP_Query( $args ); 
			global $post;

			if ($fileQuery->have_posts()) : while ($fileQuery->have_posts()): $fileQuery->the_post();

			if (get_field('file', $post)) {
				$url = get_field('file', $post);
			} elseif (get_field('announcement', $post)) {
				$url = get_field('announcement', $post);
			} elseif (get_field('presentation', $post)) {
				$url = get_field('presentation', $post);
			}
		?>

		<a class="<?= esc_attr($className); ?>" href="<?= $url; ?>" target="_blank">
			<span class="cover" style="background-image: url('<?= esc_url($cover); ?>');"></span>
			
			<div class="inner">
				<?php if ($title) { ?>
					<h3><?= $title; ?></h3>
				<?php } ?>
				
				<div class="title">
					<?php echo '<span class="meta">' . get_the_date('F j, Y') . '</span>'; ?> 
					
					<span class="name e1">
						<?php the_title(); ?>
					</span>
					
					<?= '<span class="btn">'.file_get_contents(get_template_directory() . '/assets/images/theme/icon-plus.svg').'</span>'; ?>
				</div>
			</div>
		</a>

		<?php endwhile; endif; wp_reset_query(); ?>

	<?php } ?>


</div>