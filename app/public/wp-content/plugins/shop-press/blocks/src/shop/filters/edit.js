import { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	SelectControl,
	Button,
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { Accordion, AccordionSummary, AccordionDetails } from '@mui/material';
import { ReactSortable } from 'react-sortablejs';
import apiFetch from '@wordpress/api-fetch';
import RenderIcon from '../../../../src/utils/icons';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const {
		filter_repeaters,
		display_as,
		button_style,
		drawer_label,
		drawer_icon,
		drawer_position,
		drawer_title,
		show_clear_all,
	} = attributes;

	const [ attr, setAttr ] = useState();

	useEffect( () => {
		apiFetch( {
			path: 'wc/v3/products/attributes',
		} ).then( ( data ) => {
			if ( data ) {
				const options = data?.map( ( attr ) => ( {
					value: attr.id,
					label: attr.name,
				} ) );

				setAttr( options );
			}
		} );
	}, [] );

	const templatesList = [
		{
			label: __( 'Active filters', 'shop-press' ),
			value: 'layered_nav_filters',
		},
		{
			label: __( 'Product Attributes', 'shop-press' ),
			value: 'layered_nav',
		},
		{
			label: __( 'Rating', 'shop-press' ),
			value: 'rating_filter',
		},
		{
			label: __( 'Price Range', 'shop-press' ),
			value: 'price_filter',
		},
		{
			label: __( 'order_by', 'shop-press' ),
			value: 'In Stock / On Sale',
		},
	];

	const onChangeField = ( value, index, name ) => {
		const items = [ ...filter_repeaters ];

		const filters = items?.map( ( item, idx ) => {
			return index === idx ? { ...item, [ name ]: value } : item;
		} );

		setAttributes( { filter_repeaters: filters } );
	};

	const sortList = ( filters ) => {
		setAttributes( { filter_repeaters: filters } );
	};

	const addNewItem = () => {
		const newFilter = [ { filter_repeater_select: 'product_search' } ];

		const filtersOBJ = [ ...filter_repeaters, ...newFilter ];

		setAttributes( { filter_repeaters: filtersOBJ } );
	};

	const deleteItem = ( index ) => {
		const filtersOBJ = [ ...filter_repeaters ];

		filtersOBJ.splice( index, 1 );

		setAttributes( { filter_repeaters: filtersOBJ } );
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Display as Drawer', 'shop-press' ) }
					checked={ display_as }
					onChange={ ( value ) => {
						setAttributes( { display_as: value } );
					} }
				/>

				{ display_as && (
					<>
						<SelectControl
							label={ __( 'Button style', 'shop-press' ) }
							value={ button_style }
							options={ [
								{ label: 'Text', value: 'text' },
								{ label: 'Icon', value: 'icon' },
								{ label: 'Text Icon', value: 'text_icon' },
								{ label: 'Icon Text', value: 'icon_text' },
							] }
							onChange={ ( value ) =>
								setAttributes( { button_style: value } )
							}
							__nextHasNoMarginBottom
						/>

						<TextControl
							label={ __( 'Button label', 'shop-press' ) }
							value={ drawer_label }
							onChange={ ( value ) => {
								setAttributes( { drawer_label: value } );
							} }
						/>

						{ /* TODO: add drawer_icon */ }

						<SelectControl
							label={ __( 'Drawer position', 'shop-press' ) }
							value={ drawer_position }
							options={ [
								{ label: 'Left', value: 'left' },
								{ label: 'Right', value: 'right' },
							] }
							onChange={ ( value ) =>
								setAttributes( { drawer_position: value } )
							}
							__nextHasNoMarginBottom
						/>

						<TextControl
							label={ __( 'Drawer title', 'shop-press' ) }
							value={ drawer_title }
							onChange={ ( value ) => {
								setAttributes( { drawer_title: value } );
							} }
						/>
					</>
				) }
			</PanelBody>

			<PanelBody title={ __( 'Filters', 'shop-press' ) }>
				<ToggleControl
					label={ __( 'Clear All Button', 'shop-press' ) }
					checked={ show_clear_all }
					onChange={ ( value ) => {
						setAttributes( { show_clear_all: value } );
					} }
				/>

				<ReactSortable
					className="filter-repeater-wrapper"
					list={ filter_repeaters }
					setList={ sortList }
				>
					{ filter_repeaters &&
						filter_repeaters.map( ( item, index ) => {
							return (
								<Accordion key={ index }>
									<AccordionSummary
										expandIcon={
											<RenderIcon iconName="arrow-down-circle" />
										}
										className="filter-repeater"
									>
										{ item?.title ??
											item?.filter_repeater_select }
										<Button
											className="remove-btn"
											variant="secondary"
											onClick={ () =>
												deleteItem( index )
											}
										>
											<RenderIcon iconName="remove" />
										</Button>
									</AccordionSummary>
									<AccordionDetails>
										<div className="select-filter-template">
											<SelectControl
												label={ __(
													'Template',
													'shop-press'
												) }
												value={
													item?.filter_repeater_select
												}
												onChange={ ( value ) =>
													onChangeField(
														value,
														index,
														'filter_repeater_select'
													)
												}
												options={ templatesList }
												__nextHasNoMarginBottom
											/>
										</div>

										{ item?.filter_repeater_select ===
											'layered_nav' && (
											<>
												<SelectControl
													label={ __(
														'Attributes',
														'shop-press'
													) }
													value={ item?.attribute }
													onChange={ ( value ) =>
														onChangeField(
															value,
															index,
															'attribute'
														)
													}
													options={ attr }
													__nextHasNoMarginBottom
												/>

												<SelectControl
													label={ __(
														'Display type',
														'shop-press'
													) }
													value={
														item?.attribute_display_type
													}
													onChange={ ( value ) =>
														onChangeField(
															value,
															index,
															'attribute_display_type'
														)
													}
													options={ [
														{
															label: 'Dropdown',
															value: 'dropdown',
														},
														{
															label: 'List',
															value: 'List',
														},
													] }
													__nextHasNoMarginBottom
												/>
											</>
										) }

										<TextControl
											label={ __(
												'Title',
												'shop-press'
											) }
											value={ item.title ?? '' }
											onChange={ ( value ) =>
												onChangeField(
													value,
													index,
													'title'
												)
											}
										/>

										{ item?.filter_repeater_select ===
											'price_filter' && (
											<ToggleControl
												label={ __(
													'Reset Button',
													'shop-press'
												) }
												checked={
													item?.show_reset ?? false
												}
												onChange={ ( value ) =>
													onChangeField(
														value,
														index,
														'show_reset'
													)
												}
											/>
										) }
									</AccordionDetails>
								</Accordion>
							);
						} ) }
				</ReactSortable>
				<Button variant="primary" onClick={ () => addNewItem() }>
					{ __( 'Add', 'shop-press' ) }
				</Button>
			</PanelBody>

			<PanelBody title={ __( 'Styles', 'shop-press' ) }></PanelBody>
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
				block="shop-press/shop-filters"
				attributes={ attributes }
			/>
		</div>
	);
};

export default Edit;
