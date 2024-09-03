import '../css/editor.scss';

( () => {
	// Only operate in the editor
	if ( ! window?.wp?.blocks ) {
		return;
	}

	// Hide the sticky option, force it false
	const styles = document.createElement( 'style' );
	styles.innerHTML = `
		.editor-post-panel__row:has(.editor-post-sticky__toggle-control),
		.editor-post-sticky__toggle-control,
		#sticky-span { display: none !important; }
	`;
	document.body.appendChild( styles );
	let isSticky = null;
	const { select, subscribe } = wp.data;
	const waitForEditor = subscribe( () => {
		if ( ! select( 'core/editor' ).__unstableIsEditorReady() ) {
			return;
		}
		isSticky = select( 'core/editor' ).getEditedPostAttribute( 'sticky' );
		if ( isSticky === undefined || isSticky === null ) {
			return;
		}
		const stickyControl = Array.from(
			document.querySelectorAll(
				'.edit-post-post-status .components-panel__row'
			)
		).find( ( el ) => el.innerText === 'Stick to the top of the blog' );
		if ( ! stickyControl ) {
			return;
		}
		waitForEditor();
		stickyControl.style.display = 'none';
		if ( isSticky ) {
			wp.data.dispatch( 'core/editor' ).editPost( { sticky: false } );
			wp.data.dispatch( 'core/editor' ).savePost();
		}
	} );
} )();
