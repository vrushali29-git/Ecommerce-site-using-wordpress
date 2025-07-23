import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { hide_title } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Hide Title', 'shop-press' ) }
					checked={ hide_title }
					onChange={ ( value ) => {
						setAttributes( { hide_title: value } );
					} }
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-checkout-billing-fields"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Title', 'shop-press' ) }
					selector=".sp-checkout-billing-fields > .checkout-step-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form Label', 'shop-press' ) }
					selector=".sp-checkout-billing-fields label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form Required', 'shop-press' ) }
					selector=".woocommerce .sp-checkout-billing-fields label > .required"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form Input', 'shop-press' ) }
					selector=".sp-checkout-billing-fields input, .sp-checkout-billing-fields select, .sp-checkout-billing-fields .select2-selection"
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
					block="shop-press/checkout-form-billing"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
