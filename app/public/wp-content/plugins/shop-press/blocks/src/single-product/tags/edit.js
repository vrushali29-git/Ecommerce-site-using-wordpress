import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { tag_label, tag_separator } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Tag Label', 'shop-press' ) }
					value={ tag_label }
					onChange={ ( value ) =>
						setAttributes( { tag_label: value } )
					}
				/>
				<TextControl
					label={ __( 'Separator', 'shop-press' ) }
					value={ tag_separator }
					onChange={ ( value ) =>
						setAttributes( { tag_separator: value } )
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
					selector=".sp-tags-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tags Container', 'shop-press' ) }
					selector=".sp-tags-wrapper .sp-product-tags"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tags Label', 'shop-press' ) }
					selector=".sp-tags-wrapper .sp-tag-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tag Item', 'shop-press' ) }
					selector=".sp-product-tags a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Separator', 'shop-press' ) }
					selector=".sp-tags-wrapper span.sp-product-tags span.sp-tags-separator"
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
	const { tag_label, tag_separator } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>

			<Wrapper { ...props }>
				<div className="sp-tags-wrapper">
					<span className="sp-tag-label">{ tag_label }</span>
					<span className="sp-product-tags">
						<a href="#">T-shirt</a>
						<span className="sp-tags-separator">
							{ tag_separator }
						</span>
						<a href="#">Men's T-shirt</a>
					</span>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
