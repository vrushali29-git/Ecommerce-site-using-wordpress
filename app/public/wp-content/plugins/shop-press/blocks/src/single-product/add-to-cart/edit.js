import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ true }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Container', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper table.variations"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Labels', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper table.variations th.label label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Items Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper table.variations .sp-wrapper-ul"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Item Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper table.variations .sp-wrapper-ul .sp-wrapper-item-li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Color Item Wrapper',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-color-li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Color Item', 'shop-press' ) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li .sp-item-span-color"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Color Item Selected',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-color-li.sp-selected"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Label Item Wrapper',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-label-li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Label Item', 'shop-press' ) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li .sp-item-span.item-span-text"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Label Item Selected',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-label-li.sp-selected"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Image Item Wrapper',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-image-li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Image Item', 'shop-press' ) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li .swatch-preview.swatch-image"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Variations Image Item Selected',
						'shop-press'
					) }
					selector=".sp-wrapper-ul .sp-wrapper-item-li.sp-image-li.sp-selected"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Variations Reset Button', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper .variations .reset_variations"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Quantity Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form.cart div.quantity"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Quantity Input', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form.cart div.quantity input"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Quantity Button Minus', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form .quantity .sp-quantity-control.minus"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Quantity Button Plus', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form .quantity .sp-quantity-control.plus"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Button Wrapper', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form .button"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Button Icon', 'shop-press' ) }
					selector=".sp-add-to-cart-wrapper form .button i.sp-icon"
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
					block="shop-press/product-add-to-cart"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
