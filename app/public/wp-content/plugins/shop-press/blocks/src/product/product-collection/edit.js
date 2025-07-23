import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	__experimentalNumberControl as NumberControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';
import SelectProductCategories from '../../../../src/utils/components/select-product-category';
import SelectProductTags from '../../../../src/utils/components/select-product-tag';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const {
		collection,
		template_id,
		limit,
		columns,
		orderby,
		order,
		ids,
		skus,
		cat_operator,
		tag_operator,
		category,
		tag,
		carousel,
		carousel_columns,
		slide_speed,
		show_controls,
		autoplay,
		carousel_loop,
	} = attributes;

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

	const onChangeCat = ( { e } ) => {
		const { value } = e.target;
		setAttributes( { category: value } );
	};

	const catBlock = {
		name: 'category',
		label: __( 'Categories', 'shop-press' ),
		placeholder: __( 'Search Categories...', 'shop-press' ),
		is_searchable: true,
		is_multi: true,
	};

	const onChangeTag = ( { e } ) => {
		const { value } = e.target;
		setAttributes( { tag: value } );
	};

	const tagBlock = {
		name: 'tag',
		label: __( 'Tags', 'shop-press' ),
		placeholder: __( 'Search Tags...', 'shop-press' ),
		is_searchable: true,
		is_multi: true,
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Collection', 'shop-press' ) }
					value={ collection }
					options={ [
						{
							label: 'Newest Products',
							value: 'sp-recent-products',
						},
						{
							label: 'Best Selling',
							value: 'sp-best-selling-products',
						},
						{
							label: 'Featured Products',
							value: 'sp-featured-products',
						},
						{ label: 'On Sale', value: 'sp-sales-products' },
						{ label: 'Top Rated', value: 'sp-top-rated-products' },
					] }
					onChange={ ( value ) =>
						setAttributes( { collection: value } )
					}
					__nextHasNoMarginBottom
				/>

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

				<TextControl
					label={ __( 'Product IDs', 'shop-press' ) }
					placeholder={ __( 'ex: 1,2,3,4', 'shop-press' ) }
					value={ ids }
					onChange={ ( value ) => {
						setAttributes( { ids: value } );
					} }
				/>

				<TextControl
					label={ __( 'SKUs', 'shop-press' ) }
					value={ skus }
					onChange={ ( value ) => {
						setAttributes( { skus: value } );
					} }
				/>

				<SelectControl
					label={ __( 'Category Operator', 'shop-press' ) }
					value={ cat_operator }
					options={ [
						{ label: 'Include', value: 'IN' },
						{ label: 'Exclude', value: 'NOT IN' },
					] }
					onChange={ ( value ) =>
						setAttributes( { cat_operator: value } )
					}
					__nextHasNoMarginBottom
				/>

				<SelectProductCategories
					value={ category }
					block={ catBlock }
					onChange={ onChangeCat }
				/>

				<SelectControl
					label={ __( 'Tag Operator', 'shop-press' ) }
					value={ tag_operator }
					options={ [
						{ label: 'Include', value: 'IN' },
						{ label: 'Exclude', value: 'NOT IN' },
					] }
					onChange={ ( value ) =>
						setAttributes( { tag_operator: value } )
					}
					__nextHasNoMarginBottom
				/>

				<SelectProductTags
					value={ tag }
					block={ tagBlock }
					onChange={ onChangeTag }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Carousel', 'shop-press' ) }
				initialOpen={ false }
			>
				<ToggleControl
					label={ __( 'Carousel', 'shop-press' ) }
					checked={ carousel }
					onChange={ ( value ) => {
						setAttributes( { carousel: value } );
					} }
				/>

				{ carousel && (
					<>
						<NumberControl
							onChange={ ( value ) =>
								setAttributes( { carousel_columns: value } )
							}
							value={ carousel_columns }
							label={ __( 'Carousel Items', 'shop-press' ) }
						/>

						<NumberControl
							onChange={ ( value ) =>
								setAttributes( { slide_speed: value } )
							}
							value={ slide_speed }
							label={ __( 'Slide Speed', 'shop-press' ) }
						/>

						<ToggleControl
							label={ __( 'Arrows', 'shop-press' ) }
							checked={ show_controls }
							onChange={ ( value ) => {
								setAttributes( { show_controls: value } );
							} }
						/>

						<ToggleControl
							label={ __( 'Autoplay', 'shop-press' ) }
							checked={ autoplay }
							onChange={ ( value ) => {
								setAttributes( { autoplay: value } );
							} }
						/>

						<ToggleControl
							label={ __( 'Loop', 'shop-press' ) }
							checked={ carousel_loop }
							onChange={ ( value ) => {
								setAttributes( { carousel_loop: value } );
							} }
						/>
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
				block="shop-press/product-collection"
				attributes={ attributes }
			/>
		</div>
	);
};

export default Edit;
