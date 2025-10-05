<?php
$className = 'accordion';
$className .= !empty($block['className']) ? ' ' . $block['className'] : '';
$className .= !empty($block['align']) ? ' align' . $block['align'] : '';

$is_editor = is_admin();
$open_first = get_field('display'); // ACF checkbox: true = open first
?>

<?php if (have_rows('accordion')): ?>
    <div 
        class="is-style-accordion-wrap <?= esc_attr($className); ?>" 
        <?= !$is_editor ? 'x-data="accordionGroup" x-ref="group" data-open-first="' . ($open_first ? 'true' : 'false') . '"' : '' ?>
    >
        <?php 
        $index = 0;
        while (have_rows('accordion')): the_row(); 
            $title    = get_sub_field('title');
            $subtitle = get_sub_field('subtitle');
            $content  = get_sub_field('content');
            $icon     = get_sub_field('icon');
            $is_first = $index === 0;
            $should_open = !$is_editor && $is_first && $open_first;
            $index++;
        ?>
            <div 
                class="accordion" 
                data-accordion 
                <?= $should_open ? 'data-open="true"' : '' ?>
                <?= !$is_editor ? 'x-data="accordion($el, Alpine.$data($refs.group))" x-init="init"' : '' ?>
            >
                <div 
                    class="accordion__title" 
                    data-trigger 
                    <?= !$is_editor ? '@click="toggle" :class="{ \'active\': open }"' : '' ?>
                >
                    <?php if ($icon): ?>
                        <span class="icon"><?= wp_get_attachment_image($icon, 'full'); ?></span>
                    <?php endif; ?>
                    <h3 class="title">
                        <?php if ($title): ?>
                            <span class="title-top"><?= esc_html($title); ?></span>
                        <?php endif; ?>
                        <?php if ($subtitle): ?>
                            <span class="title-bottom"><?= esc_html($subtitle); ?></span>
                        <?php endif; ?>
                    </h3>
                </div>

                <div class="accordion__panel" data-panel>
                    <div class="panel-inner">
                        <?= $content; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>