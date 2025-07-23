import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { label } = attributes;

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
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-stock"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Label', 'shop-press' ) }
					selector=".sp-stock-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Availability', 'shop-press' ) }
					selector=".sp-stock-availability"
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
				<p className="sp-stock">
					<span className="sp-stock-label">{ label }</span>
					<span className="sp-stock-availability">Out of stock</span>
				</p>
			</Wrapper>
		</div>
	);
};

export default Edit;
