import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { separator } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Separator', 'shop-press' ) }
					value={ separator }
					onChange={ ( value ) =>
						setAttributes( { separator: value } )
					}
				/>
			</PanelBody>
			<PanelBody title={ __( 'Styles', 'shop-press' ) }>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=" .sp-product-tags"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tag', 'shop-press' ) }
					selector=".sp-product-tags a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Seperator', 'shop-press' ) }
					selector=".sp-product-tags .separator"
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
				<div className="sp-product-tags">
					<a href="#" rel="tag">
						Clothing
					</a>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
