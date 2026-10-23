/**
 * Lokilo Store Locator block, editor registration.
 *
 * Dynamic (server-rendered) block: the editor preview and the front end are both
 * produced by the PHP render callback, which renders the [lokilo] directory.
 * Hand-written for the @wordpress/* script handles already shipped by WordPress
 * core, so no build step is required.
 */
( function ( blocks, blockEditor, serverSideRender, element ) {
	'use strict';

	var useBlockProps = blockEditor.useBlockProps;
	var createElement = element.createElement;

	blocks.registerBlockType( 'lokilo/store-locator', {
		edit: function () {
			return createElement(
				'div',
				useBlockProps(),
				createElement( serverSideRender, {
					block: 'lokilo/store-locator',
				} )
			);
		},
		// Dynamic block: nothing is saved to post content.
		save: function () {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.serverSideRender,
	window.wp.element
);
