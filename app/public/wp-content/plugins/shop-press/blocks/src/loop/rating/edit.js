import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes } ) => {
	const { rating_type } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Type', 'shop-press' ) }
					value={ rating_type }
					options={ [
						{ label: 'Classic', value: 'classic' },
						{ label: 'Modern', value: 'modern' },
					] }
					onChange={ ( value ) =>
						setAttributes( { rating_type: value } )
					}
					__nextHasNoMarginBottom
				/>
			</PanelBody>
			<PanelBody title={ __( 'Styles', 'shop-press' ) }>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=" .sp-loop-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Container', 'shop-press' ) }
					selector=".sp-loop-rating.sp-modern-rating .sp-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Star', 'shop-press' ) }
					selector=".sp-loop-rating.sp-modern-rating .sp-rating .sp-rating-star"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Container', 'shop-press' ) }
					selector=".sp-loop-rating.sp-classic-rating .star-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Empty Stars', 'shop-press' ) }
					selector=".sp-loop-rating.sp-classic-rating .star-rating::before"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Stars', 'shop-press' ) }
					selector=".sp-loop-rating.sp-classic-rating .star-rating span"
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
	const { rating_type } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>

			<Wrapper { ...props }>
				{ rating_type === 'modern' && (
					<div className="woocommerce-product-rating sp-loop-rating sp-modern-rating">
						<div className="sp-rating">
							<span className="sp-rating-star">S</span>4.5
						</div>
					</div>
				) }

				{ rating_type === 'classic' && (
					<div className="woocommerce-product-rating sp-loop-rating sp-classic-rating">
						<div
							className="star-rating"
							role="img"
							aria-label="Rated 4 out of 5"
						>
							<span style={ { width: '80%' } }></span>
						</div>
					</div>
				) }
			</Wrapper>
		</div>
	);
};

export default Edit;
