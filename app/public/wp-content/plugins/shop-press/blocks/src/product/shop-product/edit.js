import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { template_id, infinite_scroll, load_more_button, loadmore_text } =
		attributes;

	const [ templates, setTemplates ] = useState();

	useEffect( () => {
		apiFetch( {
			path: '/sp-block/loop-templates',
		} ).then( ( data ) => {
			if ( data ) {
				const options = Object.entries( data )?.map( ( template ) => ( {
					value: template[ 0 ],
					label: template[ 1 ],
				} ) );

				setTemplates( options );
			}
		} );
	}, [] );

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Template', 'shop-press' ) }
					value={ template_id }
					options={ templates ?? [] }
					onChange={ ( value ) =>
						setAttributes( { template_id: value } )
					}
					__nextHasNoMarginBottom
				/>

				{ ! load_more_button && (
					<ToggleControl
						label={ __( 'Infinite scroll', 'shop-press' ) }
						checked={ infinite_scroll }
						onChange={ ( value ) => {
							setAttributes( { infinite_scroll: value } );
						} }
					/>
				) }

				{ ! infinite_scroll && (
					<>
						<ToggleControl
							label={ __( 'Load More Button', 'shop-press' ) }
							checked={ load_more_button }
							onChange={ ( value ) => {
								setAttributes( { load_more_button: value } );
							} }
						/>

						{ load_more_button && (
							<TextControl
								label={ __(
									'Load More Button Text',
									'shop-press'
								) }
								value={ loadmore_text }
								onChange={ ( value ) => {
									setAttributes( { loadmore_text: value } );
								} }
							/>
						) }
					</>
				) }
			</PanelBody>
		</InspectorControls>
	);
};

const Edit = ( props ) => {
	const { attributes, setAttributes, clientId } = props;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>

			<ServerSideRender
				block="shop-press/shop-product"
				attributes={ attributes }
			/>
		</div>
	);
};

export default Edit;
