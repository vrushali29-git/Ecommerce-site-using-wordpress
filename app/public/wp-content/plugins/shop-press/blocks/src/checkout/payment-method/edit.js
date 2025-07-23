import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	ToggleControl,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { button_style, button_text, field_icon } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				{ /* <ToggleControl
					label={__('Show Field Icon', 'shop-press')}
					checked={field_icon}
					onChange={(value) => {
						setAttributes({ field_icon: value });
					}}
				/>

				<SelectControl
					label={__('Show Field Icon', 'shop-press')}
					value={button_style}
					options={[
						{ label: __('Text', 'shop-press'), value: 'text' },
						{ label: __('Icon', 'shop-press'), value: 'icon' },
						{
							label: __('Text Icon', 'shop-press'),
							value: 'button_icon',
						},
						{
							label: __('Icon Text', 'shop-press'),
							value: 'icon_button',
						},
					]}
					onChange={(value) => setAttributes({ button_style: value })}
					__nextHasNoMarginBottom
				/> */ }

				<TextControl
					label={ __( 'Button Text', 'shop-press' ) }
					value={ button_text }
					onChange={ ( value ) =>
						setAttributes( { button_text: value } )
					}
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector="#payment"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Payment Methods Wrapper', 'shop-press' ) }
					selector=".wc_payment_methods"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Payment Methods Items', 'shop-press' ) }
					selector=".wc_payment_method"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Payment Method Input', 'shop-press' ) }
					selector=".wc_payment_method input"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Payment Method Label', 'shop-press' ) }
					selector=".wc_payment_method label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Payment Method Box', 'shop-press' ) }
					selector="#payment div.payment_box"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Place Order Wrapper', 'shop-press' ) }
					selector=".form-row.place-order"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Privacy Policy Wrapper', 'shop-press' ) }
					selector=".woocommerce-privacy-policy-text"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Privacy Policy Text', 'shop-press' ) }
					selector=".woocommerce-privacy-policy-text p"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Privacy Policy Link', 'shop-press' ) }
					selector=".woocommerce-privacy-policy-text a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Place Order Button', 'shop-press' ) }
					selector=".place-order button#place_order"
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
					block="shop-press/checkout-payment"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
