// disable publishing prechecks
window.wp.data.dispatch("core/editor").disablePublishSidebar();


// unregister default block styles
wp.domReady(function () {
	wp.blocks.unregisterBlockStyle("core/image", "rounded");
	wp.blocks.unregisterBlockStyle("core/table", "stripes");
	wp.blocks.unregisterBlockStyle("core/quote", "plain");
	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
});

// button
wp.blocks.registerBlockStyle('core/button', {
	name: 'white',
	label: 'White',
});

// heading
wp.blocks.registerBlockStyle('core/heading', {
	name: 'small-title',
	label: 'Small Title',
});
wp.blocks.registerBlockStyle('core/heading', {
	name: 'top-border',
	label: 'Top Border',
});

// paragraphs
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "maxwidth",
	label: "Max Width",
});
wp.blocks.registerBlockStyle('core/paragraph', {
	name: 'intro',
	label: 'Intro',
});
wp.blocks.registerBlockStyle("core/paragraph", {
	name: "footnote",
	label: "Footnote",
});
// wp.blocks.registerBlockStyle("core/paragraph", {
	// name: "email",
	// label: "Email",
// });
// wp.blocks.registerBlockStyle("core/paragraph", {
	// name: "phone",
	// label: "Phone",
// });
// wp.blocks.registerBlockStyle("core/paragraph", {
	// name: "location",
	// label: "Location",
// });
// wp.blocks.registerBlockStyle("core/paragraph", {
	// name: "linkedin",
	// label: "LinkedIn",
// });
// wp.blocks.registerBlockStyle("core/paragraph", {
	// name: "x",
	// label: "X",
// });


// quote
// wp.blocks.registerBlockStyle("core/quote", {
	// name: "pullquote",
	// label: "Pullquote",
// })


// image
wp.blocks.registerBlockStyle("core/image", {
	name: "breakout-left",
	label: "Breakout Left",
});
wp.blocks.registerBlockStyle("core/image", {
	name: "breakout-right",
	label: "Breakout Right",
});

// table
wp.blocks.registerBlockStyle('core/table', {
	name: 'swipe',
	label: 'Swipe (mobile)',
});


// columns
wp.blocks.registerBlockStyle("core/columns", {
	name: "no-gap",
	label: "No Gap",
});
wp.blocks.registerBlockStyle("core/columns", {
	name: "sm-gap",
	label: "Small Gap",
});
wp.blocks.registerBlockStyle("core/columns", {
	name: "lg-gap",
	label: "Large Gap",
});
wp.blocks.registerBlockStyle("core/column", {
	name: "btn-bottom",
	label: "Bottom Button",
});
wp.blocks.registerBlockStyle("core/column", {
	name: "box",
	label: "Box",
});
// wp.blocks.registerBlockStyle("core/columns", {
	// name: "grid",
	// label: "Grid",
// });


// group
wp.blocks.registerBlockStyle("core/group", {
	name: "sage-green",
	label: "Sage to green horisontal",
});
wp.blocks.registerBlockStyle("core/group", {
	name: "linen-sage",
	label: "Linen to sage horisontal",
});
wp.blocks.registerBlockStyle("core/group", {
	name: "pine-green",
	label: "Pine to green vertical",
});