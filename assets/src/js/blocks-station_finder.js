/**
 * Custom Gutenberg Block for Station Finder
 */
( function( blocks, element ) {
    var el = element.createElement;
 
    var blockStyle = {
        backgroundColor: '#ccc',
        borderRadius: '3px',
        color: '#fff',
        padding: '20px',
        textAlign: 'center'
    };
 
    blocks.registerBlockType( 'cumulus-gutenberg/station-finder', {
        title: 'Station Finder',
        icon: {
        	src: 'format-audio',
        	foreground: '#3399cc'
        },
        category: 'widgets',
        edit: function() {
            return el(
                'div',
                { style: blockStyle },
                'Station Finder Widget'
            );
        },
        save: function() {
            return el(
                'div',
                { id: 'station-finder-widget' },
                ''
            );
        }
    } );
}(
    window.wp.blocks,
    window.wp.element
) );