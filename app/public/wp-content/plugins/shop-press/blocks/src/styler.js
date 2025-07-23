import md5 from 'md5';
import { store as editPostStore } from '@wordpress/edit-post';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { ReactComponent as Styler_icon } from '../src/icons/styler-icon.svg';


function Styler( props ) {
	const { deviceType } = useSelect( ( select ) => {
		return {
			deviceType:
				select( editPostStore ).__experimentalGetPreviewDeviceType(),
		};
	}, [] );

	const { attributes, setAttributes } = props;

	if ( styler.currentSelectedBlock !== props.clientId ) {
		styler.currentSelectedBlock = props.clientId;
		wp.hooks.doAction( 'StylerNeedsDestroy', 'GutenbergStyler' );
	}

	const md5Code = md5( props.selector );

	var wrapper = props.wrapper;

	var stylerData = attributes[ 'styler' ];

	if ( ! props.clientId ) {
		return false;
	}

	if ( styler ) {
		if (
			typeof styler.GeneratedStyles.gutenberg[ 'wrappers' ] ===
			'undefined'
		) {
			styler.GeneratedStyles.gutenberg[ 'wrappers' ] = {};
		}
	}

	if ( styler.GeneratedStyles.gutenberg[ 'wrappers' ][ props.clientId ] ) {
		if (
			styler.GeneratedStyles.gutenberg[ 'wrappers' ][ props.clientId ] !==
			props.wrapperID
		) {
			wrapper = wrapper.replaceAll(
				attributes[ 'wrapperID' ],
				styler.GeneratedStyles.gutenberg[ 'wrappers' ][ props.clientId ]
			);
		}
	}

	const match = stylerData.match( /.wrapper-(.*?) / );

	if ( match && wrapper !== match[ 0 ].trim() ) {
		stylerData = stylerData.replaceAll( match[ 0 ].trim(), wrapper );

		const cidReg = new RegExp( /\"cid\":\"(.*?)\"/, 'g' );

		let cidMatch;
		while ( ( cidMatch = cidReg.exec( stylerData ) ) ) {
			var newCid = Math.floor(
				window.performance &&
					window.performance.now &&
					window.performance.timeOrigin
					? window.performance.now() + window.performance.timeOrigin
					: Date.now()
			).toString();
			stylerData = stylerData.replaceAll( cidMatch[ 1 ].trim(), newCid );
		}

		if ( typeof props.setAttributes !== 'undefined' ) {
			props.setAttributes( {
				styler: stylerData,
			} );
		}
		wp.hooks.doAction(
			'StylerNeedsUpdateLive',
			'GutenbergStyler',
			stylerData
		);
	}

	if ( styler.ActiveDevice !== deviceType ) {
		styler.ActiveDevice = deviceType;
		if ( styler.doDestroy === true ) {
			styler.doDestroy = false;
			wp.hooks.doAction( 'StylerNeedsDestroy', 'GutenbergStyler' );
		}
		wp.hooks.doAction(
			'StylerNeedsUpdateLive',
			'GutenbergStyler',
			stylerData
		);
	}

	stylerData = JSON.parse( stylerData );

	var dataObject = {};

	if ( ! stylerData ) {
		dataObject = {
			cid: '',
			data: {},
		};
	} else if ( typeof stylerData[ md5Code ] === 'undefined' ) {
		dataObject = {
			cid: '',
			data: {},
		};
	} else {
		dataObject = stylerData[ md5Code ];
	}

	const handleChanges = ( key, value ) => {
		switch ( key ) {
			case 'cid':
				dataObject[ 'cid' ] = value;
				break;
			case 'data':
				dataObject[ 'data' ] = value;
				break;
		}

		stylerData[ md5Code ] = dataObject;

		wp.hooks.doAction(
			'StylerNeedsUpdateLive',
			'GutenbergStyler',
			stylerData
		);
		setAttributes( {
			styler: JSON.stringify( stylerData ),
		} );
	};

	return (
		<div className="climax-style">
			<div className="gutenberg-styler-control-field">
				<div className="gutenberg-styler-control-input-wrapper">
					<label className="gutenberg-control-title">
						{ props.label }
					</label>
					<div
						className="tmp-styler-gutenberg-dialog-btn"
						data-title={ props.label }
						data-id="gutenberg"
						data-parent-id=""
						data-selector={ props.selector }
						data-wrapper={ wrapper }
						data-type=""
						data-active-device={ deviceType.toLowerCase() }
						data-field-id={ md5Code }
						data-is-svg={ props.isSVG }
						data-is-input={ props.isInput }
						data-hover-selector={ props.hoverSelector }
					>
						<input
							type="hidden"
							value={
								typeof dataObject[ 'data' ] === 'string'
									? dataObject[ 'data' ]
									: JSON.stringify( dataObject[ 'data' ] )
							}
							data-setting="stdata"
							onInput={ ( input ) => {
								var value = input.target.value;
								handleChanges( 'data', value );
							} }
						/>
						<input
							type="hidden"
							value={ dataObject[ 'cid' ] }
							data-setting="cid"
							onInput={ ( input ) => {
								var value = input.target.value;
								handleChanges( 'cid', value );
							} }
						/>

						<Styler_icon/>

					</div>
				</div>
			</div>
			{ typeof props.description !== 'undefined' ? (
				<div className="gutenberg-styler-control-field-description">
					{ props.description }
				</div>
			) : (
				''
			) }
		</div>
	);
}

Styler.defaultProps = {
	label: '',
	selector: '',
	wrapper: '',
	isSVG: false,
	isInput: false,
	hoverSelector: '',
	description: '',
};

export default Styler;
