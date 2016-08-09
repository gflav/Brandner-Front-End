<?php

// TODO: update
// slideshow
if(function_exists('attach_fields')) {
  
	attach_fields('Slide Options', 'bd_slide', array(
		array(
			'title' => 'Intro',
			'details' => 'Intro that shows right above product title',
			'slug' => 'intro',
			'type' => 'text',
		),
		array(
			'title' => 'Position',
			'details' => 'Position of text on the slide',
			'slug' => 'position',
			'type' => 'select',
			'values' => array(
				// 'select-value' => 'Displayed Name',
				'lt' => 'Left Top',
				'lb' => 'Left Bottom',
			)
		),
		array(
			'title' => 'Color',
			'details' => 'Text color for slide',
			'slug' => 'color',
			'type' => 'select',
			'values' => array(
				'white' => 'White',
				'black' => 'Black',
			),
		),
	));

	attach_fields('Dimensions', 'bd_product', array(
		array(
			'title' => 'Dimensions',
			'details' => 'Dimensionss of the product eg. 42"L X 30"W X 20"T',
			'slug' => 'dimensions',
			'type' => 'text',
		),
		array(
			'title' => 'Download',
			'details' => 'Download Link',
			'slug' => 'download',
			'type' => 'text',
		),
	));

	attach_fields('Image', 'finish', array(
		array(
			'title' => 'Public Image',
			'details' => 'Image shown on the finishes page.',
			'slug' => 'pub-image',
			'type' => 'media',
		),
	));

	attach_fields( 'Image', 'project', array(
		array(
			'title' => 'Large Image',
			'details' => 'Large image to be used as background on portfolio archive page.',
			'slug' => 'portfolio-large-image',
			'type' => 'media',
		),
	) );
  
}