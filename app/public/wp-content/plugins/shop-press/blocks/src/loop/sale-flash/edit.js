import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { cart_icon, cart_icon_pos, label } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Label', 'shop-press' ) }
					value={ label }
					onChange={ ( value ) => setAttributes( { label: value } ) }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Styles', 'shop-press' ) }>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-onsale"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Label', 'shop-press' ) }
					selector=".sp-onsale span.onsale"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon', 'shop-press' ) }
					selector=".sp-onsale .sp-icon"
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
	const { label } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>

			<Wrapper { ...props }>
				<div className="sp-onsale">
					<span className="onsale">{ label }</span>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
