import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { category_label, category_separator } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Category Label', 'shop-press' ) }
					value={ category_label }
					onChange={ ( value ) =>
						setAttributes( { category_label: value } )
					}
				/>
				<TextControl
					label={ __( 'Separator', 'shop-press' ) }
					value={ category_separator }
					onChange={ ( value ) =>
						setAttributes( { category_separator: value } )
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
					selector=".sp-categories-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Categories Container', 'shop-press' ) }
					selector=".sp-categories-wrapper .sp-product-categories"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Label', 'shop-press' ) }
					selector=".sp-categories-wrapper .sp-category-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Category Item', 'shop-press' ) }
					selector=".sp-product-categories a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Separator', 'shop-press' ) }
					selector=".sp-product-categories span.sp-cat-separator"
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
	const { category_label, category_separator } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>
			<Wrapper { ...props }>
				<div className="sp-categories-wrapper">
					<span className="sp-category-label">
						{ category_label }
					</span>
					<span className="sp-product-categories">
						<a href="#">Clothing</a>
						<span className="sp-cat-separator">
							{ category_separator }
						</span>
						<a href="#">Hoodies</a>
					</span>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
