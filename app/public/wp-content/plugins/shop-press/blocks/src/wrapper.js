const ManageWrapperID = ( clientId, attributes, ...props ) => {
	if ( ! clientId ) {
		return;
	}

	if ( styler ) {
		if (
			typeof styler.GeneratedStyles.gutenberg[ 'wrappers' ] ===
			'undefined'
		) {
			styler.GeneratedStyles.gutenberg[ 'wrappers' ] = {};
		}
	}

	const pageID = styler.currentPageID
		? styler.currentPageID
		: wp.data.select( 'core/editor' ).getCurrentPostId();

	const newWrapperID =
		'wrapper-' +
		Date.now().toString( 36 ) +
		Math.random().toString( 36 ).substr( 20 ) +
		'-' +
		pageID;

	styler.GeneratedStyles.gutenberg[ 'wrappers' ][ clientId ] = newWrapperID;

	return newWrapperID;
};

const isValidWrapper = ( wrapper, clientId ) => {
	var validate = true;

	var pageID = styler.currentPageID
		? styler.currentPageID
		: wp.data.select( 'core/editor' ).getCurrentPostId();
	const arrayString = wrapper.split( '-' );

	pageID = pageID === null ? '' : pageID;

	if ( arrayString.at( -1 ).toString() !== pageID.toString() ) {
		return false;
	}

	var wrappers = styler.GeneratedStyles.gutenberg[ 'wrappers' ];
	Object.keys( wrappers ).map( ( key ) => {
		if ( wrappers[ key ] === wrapper && key !== clientId ) {
			validate = false;
		}
	} );

	return validate;
};

function Wrapper( props ) {
	const { TagName, clientId, children, attributes, className } = props;
	var classname = typeof className === 'undefined' ? '' : className;

	if ( ! clientId ) {
		var wrapperID = '';

		if ( attributes ) {
			wrapperID = attributes[ 'wrapperID' ];
		}

		if ( wrapperID && typeof classname !== 'undefined' ) {
			classname = classname + ' ' + wrapperID;
		} else {
			classname = wrapperID;
		}

		return wp.element.createElement(
			TagName,
			{ ...props, className: classname },
			children
		);
	}

	if ( styler ) {
		if ( typeof styler.GeneratedStyles === 'undefined' ) {
			styler.GeneratedStyles = {};
		}

		if ( typeof styler.GeneratedStyles.gutenberg === 'undefined' ) {
			styler.GeneratedStyles.gutenberg = {};
		}

		if (
			typeof styler.GeneratedStyles.gutenberg[ 'wrappers' ] ===
			'undefined'
		) {
			styler.GeneratedStyles.gutenberg[ 'wrappers' ] = {};
		}
	}

	var wrapperID = attributes[ 'wrapperID' ];

	if (
		typeof styler?.GeneratedStyles?.gutenberg?.[ 'wrappers' ]?.[
			clientId
		] === 'undefined'
	) {
		if ( isValidWrapper( wrapperID, clientId ) ) {
			styler.GeneratedStyles.gutenberg[ 'wrappers' ][ clientId ] =
				wrapperID;
		} else {
			wrapperID = ManageWrapperID( clientId, attributes, props );
		}
	} else if ( ! styler.GeneratedStyles.gutenberg[ 'wrappers' ][ clientId ] ) {
		if ( isValidWrapper( wrapperID, clientId ) ) {
			styler.GeneratedStyles.gutenberg[ 'wrappers' ][ clientId ] =
				wrapperID;
		} else {
			wrapperID = ManageWrapperID( clientId, attributes, props );
		}
	} else {
		wrapperID = styler.GeneratedStyles.gutenberg[ 'wrappers' ][ clientId ];
	}

	if ( wrapperID !== attributes[ 'wrapperID' ] ) {
		if ( typeof props.setAttributes !== 'undefined' ) {
			props.setAttributes( {
				wrapperID: wrapperID,
			} );
		}
	}

	if ( wrapperID ) {
		classname = classname + ' ' + wrapperID;
	}

	return wp.element.createElement(
		TagName,
		{ ...props, className: classname },
		children
	);
}

Wrapper.defaultProps = {
	TagName: 'div',
};

export default Wrapper;
