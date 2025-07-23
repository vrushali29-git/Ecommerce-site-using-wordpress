import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { hover_details } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Product details on hover', 'shop-press' ) }
					value={ hover_details }
					options={ [
						{ label: 'None', value: 'none' },
						{ label: 'Title', value: 'title' },
						{ label: 'Thumbnail', value: 'thumb' },
						{ label: 'Title + Thumbnail', value: 'tthumb' },
						{ label: 'Title + Price', value: 'tprice' },
						{
							label: 'Title + Thumbnail + Price',
							value: 'tthumbprice',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { hover_details: value } )
					}
					__nextHasNoMarginBottom
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-navigation"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Item Wrapper', 'shop-press' ) }
					selector=".sp-navigation .sp-navigation-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Navigation', 'shop-press' ) }
					selector=".sp-navigation .sp-navigation-wrap .sp-navigation-link"
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
					block="shop-press/product-navigation"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
