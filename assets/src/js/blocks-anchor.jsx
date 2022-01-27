/**
 * Custom Gutenberg Block for Station Finder
 */
( function( blocks, element ) {
    var el = element.createElement;

    var blockStyle = {
        backgroundColor: '#DDD',
        borderRadius: '3px',
        color: '#888',
        padding: '5px',
        textAlign: 'center'
    };

    blocks.registerBlockType( 'cumulus-gutenberg/anchor', {
        title: 'Anchor',
        icon: {
        	src: 'admin-links',
        	foreground: '#3399cc'
        },
        category: 'layout',
        supports: {
            anchor: true
        },
        edit: function() {
            var div = el(
                'div',
                { style: blockStyle },
                'Anchor'
            );
            return div;
        },
        save: function() {
            return el(
                'div',
                { class: 'cm-anchor'},
                ''
            );
        }
    } );
}(
    window.wp.blocks,
    window.wp.element
) );