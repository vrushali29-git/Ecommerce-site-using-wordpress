import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { link_to_product, link_target, tag } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Title HTML Tag', 'shop-press' ) }
					value={ tag }
					options={ [
						{ label: 'h1', value: 'h1' },
						{ label: 'h2', value: 'h2' },
						{ label: 'h3', value: 'h3' },
						{ label: 'h4', value: 'h4' },
						{ label: 'h5', value: 'h5' },
						{ label: 'h6', value: 'h6' },
						{ label: 'p', value: 'p' },
						{ label: 'span', value: 'span' },
					] }
					onChange={ ( value ) => setAttributes( { tag: value } ) }
					__nextHasNoMarginBottom
				/>

				<ToggleControl
					label={ __( 'Link to Product', 'shop-press' ) }
					checked={ link_to_product }
					onChange={ () =>
						setAttributes( { link_to_product: ! link_to_product } )
					}
				/>

				<SelectControl
					label={ __( 'Open link in', 'shop-press' ) }
					value={ link_target }
					options={ [
						{ label: 'Current Window', value: '_self' },
						{ label: 'New Window', value: '_blank' },
					] }
					onChange={ ( value ) =>
						setAttributes( { link_target: value } )
					}
					__nextHasNoMarginBottom
				/>
			</PanelBody>
			<PanelBody title={ __( 'Styles', 'shop-press' ) }>
				<Styler
					clientId={ clientId }
					label={ __( 'Title', 'shop-press' ) }
					selector=" .sp-product-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
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

			<Wrapper { ...props }>
				<ServerSideRender
					block="shop-press/loop-title"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
