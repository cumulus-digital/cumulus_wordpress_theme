/**
 * Custom Gutenberg Block for image flipper
 */
( function( blocks, element ) {
	const { Button, Panel, PanelBody, PanelRow, SelectControl, RangeControl } = wp.components;
	const { InspectorControls } = wp.editor;
	const { withSelect } = wp.data;

	function categorySelect( cats ) {
		console.log('DING');
		return (
			<SelectControl
				label="Image Category"
				value={ attributes.category }
				options={ cats.category }
				onChange={ (cat) => { setAttributes({ category: cat }) } }
			/>
		);
	}
	const categoryList = withSelect( ( select ) => ( {
		category: select( 'core' ).getEntityRecords( 'taxonomy', 'category', { hide_empty: false } ),
	} ) )( categorySelect);

	const el = element.createElement;

	var blockStyle = {
		backgroundColor: '#aaa',
		borderRadius: '3px',
		color: '#fff',
		padding: '20px',
		textAlign: 'center'
	};

	blocks.registerBlockType( 'cumulus-gutenberg/image-flipper', {
		title: 'Image Flipper',
		icon: {
			src: 'images-alt2',
			foreground: '#3399cc'
		},
		category: 'common',
		attributes: {
			category: {
				attribute: 'string',
				default: null,
			},
		},
		edit: withSelect( function( select ) {
			return {
				categories: select( 'core' ).getEntityRecords( 'taxonomy', 'category' )
			};
		} )( function( props ) {

			let ret;

			if ( ! props.categories ) {
				return (
					<div>
						<div className={ props.className + ` image-flipper` } style={ blockStyle } data-category={ props.attributes.category }>
							Image Flipper!
						</div>
						<InspectorControls>
							<PanelBody title="Image Flipper Options">
								<PanelRow>
									Loading categories...
								</PanelRow>
							</PanelBody>
						</InspectorControls>
					</div>
				);
			}

			if ( props.categories.length === 0 ) {
				return (
					<div>
						<div className={ props.className + ` image-flipper` } style={ blockStyle } data-category={ props.attributes.category }>
							Image Flipper!
						</div>
						<InspectorControls>
							<PanelBody title="Image Flipper Options">
								<PanelRow>
									No categories exist!
								</PanelRow>
							</PanelBody>
						</InspectorControls>
					</div>
				);
			}

			let categories = [ { label: 'Select a category', value: null }];
			props.categories.forEach(function(cat) {
				categories.push(
					{ label: cat.name, value: cat.id }
				);
			});

			return (
				<div>
					<div className={ props.className + ` image-flipper` } style={ blockStyle } data-category={ props.attributes.category }>
						Image Flipper!
					</div>
					<InspectorControls>
						<PanelBody title="Image Flipper Options">
							<PanelRow>
								<SelectControl
									label="Image Category:"
									value={ props.attributes.category }
									options={ categories }
									onChange={ (cat) => { props.setAttributes( { category: cat } ) } }
								/>
							</PanelRow>
						</PanelBody>
					</InspectorControls>
				</div>
			);
		} ),
		save( { attributes } ) {
			return (
				<div className={ `image-flipper` } data-category={ attributes.category }></div>
			);
		}
	} );
}(
	window.wp.blocks,
	window.wp.element
) );