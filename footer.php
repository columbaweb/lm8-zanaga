<?php
	if (!class_exists('ACF')) return;

	$options = get_field('footer', 'option');
	$footer_widgets = isset($options['footer_column_count']) ? $options['footer_column_count'] : 4;
	$copyright = $options['copyright_statement'] ?? '';
	$b2t = $options['back_to_top'] ?? null;
	$show_in_footer = get_field('show_in_footer', 'option');
	$display_in = get_field('display_in', 'option');
?>

</main>

<div class="footer-branding">
	<?= file_get_contents(get_stylesheet_directory() . '/assets/images/theme/logo.svg'); ?>
</div>

<footer id="colophon" class="has-global-padding">
	<div class="wrap">
		<div class="columns">
			<?php
				$columns = [];

				for ($i = 1; $i <= $footer_widgets; $i++) {
					ob_start();
					?>
					<div class="col col-<?= $i; ?>">
						<?php dynamic_sidebar("footer-$i"); ?>

						<?php if ($i == $footer_widgets) : ?>
							
							<?php if ($show_in_footer && $display_in === 'column') {
								get_template_part('parts/part', 'social-links');
							} ?>
							
							<p class="copyright">
								&copy; <?= date('Y'); ?> <?= get_bloginfo('name'); ?>. <?= $copyright; ?>
							</p>
						<?php endif; ?>
					</div>
					<?php
					$columns[] = ob_get_clean();
				}

				echo implode("\n", $columns);
			?>
		</div>
	</div>

	<div class="subfooter">
		<div class="wrap">

			<?php 
				if ($show_in_footer && $display_in === 'subfooter') {
					get_template_part('parts/part', 'social-links');
				}
			?>
			
			<p class="credit">
				Website by <a href="https://luminate.works" target="_blank" rel="noopener" title="Luminate" aria-label="Website by Luminate">Luminate works</a>
			</p>
			
			<div class="footer-deco">
				<svg class="b2t" aria-label="Scroll to top" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88 88" fill="none"><path class="circle" fill="#fff" d="M44 .007c-10.943 0-20.947 3.997-28.646 10.616C8.43 16.57 3.377 24.637 1.197 33.813a42.869 42.869 0 0 0-.695 3.516 42.615 42.615 0 0 0-.376 3.517A44.483 44.483 0 0 0 0 44c0 12.152 4.928 23.145 12.893 31.101A43.949 43.949 0 0 0 44 88a43.42 43.42 0 0 0 15.723-2.903 42.444 42.444 0 0 0 3.517-1.522C77.9 76.439 88 61.398 88 44 88 19.703 68.294 0 43.993 0L44 .007Z"/><path fill="#FF6139" d="M45.123 56.101c0-.255.01-.52.032-.83.024-.34.057-.644.1-.931a11.682 11.682 0 0 1 2.837-5.998l13.433-13.61v-10.67H27.03v11.896h15.948L26.813 52.124v10.674h20.433l-.048-.065a11.56 11.56 0 0 1-2.075-6.63V56.1Z"/><path fill="#FF6139" d="M56.752 47.828a8.23 8.23 0 0 0-5.385 1.996 8.275 8.275 0 0 0-2.792 5.022 8.038 8.038 0 0 0-.07.66 8.24 8.24 0 0 0 2.4 6.441 8.28 8.28 0 0 0 5.847 2.426 8.162 8.162 0 0 0 3.617-.832 8.274 8.274 0 0 0-3.619-15.714l.002.001Z"/></svg>
			</div>
			
		</div>
	</div>

	<?php if ($b2t) : ?>
		<button class="b2t" aria-label="Scroll to top">
			<?= file_get_contents(get_template_directory() . '/assets/images/theme/b2t.svg'); ?>
		</button>
	<?php endif; ?>
</footer>

<?php wp_footer(); ?>

</body>
</html>