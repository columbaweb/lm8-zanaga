<?php
	$id = !empty($block['anchor']) ? $block['anchor'] : 'latest-documents-' . $block['id'];
	$className = 'latest-document';

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

<div class="<?= esc_attr($className); ?>">
    <?php if ($type === 'single') { ?>
        <a class="file" href="<?= esc_url($file); ?>" target="<?= esc_attr($target); ?>" title="<?= $sub; ?> <?= $title; ?>">
            
			<span class="cover-wrap">
                <span class="cover" style="background-image: url('<?= esc_url($cover); ?>');"></span>
            </span>
            
			<div class="title">
                <?php if ($title) { ?>
					<h3 class="e1">
						<span class="name"><?= $title; ?></span>
						<?php 
							if ($sub) {
								echo '<span class="meta">' . $sub . '</span>';
							} 
						?>
					</h3>
				<?php } ?>
				<?= '<span class="btn">'.file_get_contents(get_template_directory() . '/assets/images/theme/arrow-right.svg').'</span>'; ?>
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

		<a class="file" href="<?= $url; ?>" target="_blank">
			<span class="cover-wrap">
				<span class="cover" style="background-image: url('<?= $cover; ?>');"></span>
			</span>
			
			<div class="title">
				<h3 class="e1">
					<span class="name">
						<?php if ($title) { 
							echo $title; 
						} else { 
							the_title();
						} 						
							echo '<span class="meta">' . get_the_date('F j, Y') . '</span>';
						?>
					</span>
				</h3>

				<?= '<span class="btn">'.file_get_contents(get_template_directory() . '/assets/images/theme/arrow-right.svg').'</span>'; ?>
            </div>
		</a>

		<?php endwhile; endif; wp_reset_query(); ?>

	<?php } ?>


</div>