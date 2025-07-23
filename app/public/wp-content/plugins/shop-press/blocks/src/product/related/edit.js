import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { template_id, limit, columns, orderby, order } = attributes;

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

				<NumberControl
					onChange={ ( value ) => setAttributes( { limit: value } ) }
					value={ limit }
					label={ __( 'Limit', 'shop-press' ) }
				/>

				<NumberControl
					onChange={ ( value ) =>
						setAttributes( { columns: value } )
					}
					value={ columns }
					label={ __( 'Columns', 'shop-press' ) }
				/>

				<SelectControl
					label={ __( 'Order By', 'shop-press' ) }
					value={ orderby }
					options={ [
						{ label: 'ID', value: 'id' },
						{ label: 'Title', value: 'title' },
						{ label: 'Date', value: 'date' },
						{ label: 'Random', value: 'rand' },
						{ label: 'Price', value: 'price' },
						{ label: 'Popularity', value: 'popularity' },
						{ label: 'Rating', value: 'rating' },
					] }
					onChange={ ( value ) =>
						setAttributes( { orderby: value } )
					}
					__nextHasNoMarginBottom
				/>

				<SelectControl
					label={ __( 'Order', 'shop-press' ) }
					value={ order }
					options={ [
						{ label: 'ASC', value: 'ASC' },
						{ label: 'DESC', value: 'DESC' },
					] }
					onChange={ ( value ) => setAttributes( { order: value } ) }
					__nextHasNoMarginBottom
				/>
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
				block="shop-press/related-product"
				attributes={ attributes }
			/>
		</div>
	);
};

export default Edit;
