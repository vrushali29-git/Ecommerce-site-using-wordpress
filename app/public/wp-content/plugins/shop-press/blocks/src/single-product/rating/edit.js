import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { rating_type, show_review_counter } = attributes;

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
						{ label: 'Modern', value: 'modern' },
						{ label: 'Classic', value: 'classic' },
					] }
					onChange={ ( value ) =>
						setAttributes( { rating_type: value } )
					}
					__nextHasNoMarginBottom
				/>
				<ToggleControl
					label={ __( 'Show review counter', 'shop-press' ) }
					checked={ show_review_counter }
					onChange={ () =>
						setAttributes( {
							show_review_counter: ! show_review_counter,
						} )
					}
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ true }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-single-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Container', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating.sp-modern-rating .sp-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Modern Star', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating.sp-modern-rating .sp-rating .sp-rating-star"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Container', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating.sp-classic-rating .star-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Empty Star', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating.sp-classic-rating .star-rating::before"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Classic Full Star', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating.sp-classic-rating .star-rating span"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Reviews Count', 'shop-press' ) }
					selector="a.woocommerce-review-link span.count"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Review Link', 'shop-press' ) }
					selector=".woocommerce-product-rating.sp-single-rating a.woocommerce-review-link"
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
	const { rating_type, show_review_counter } = attributes;
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
					<div className="woocommerce-product-rating sp-single-rating sp-modern-rating">
						<div className="sp-rating">
							<span className="sp-rating-star">S</span>
							4.0
						</div>
						{ show_review_counter && (
							<a
								href="#reviews"
								className="woocommerce-review-link"
								rel="nofollow"
							>
								<span className="count">1</span>
								review
							</a>
						) }
					</div>
				) }

				{ rating_type === 'classic' && (
					<div className="woocommerce-product-rating sp-single-rating sp-classNameic-rating">
						<div
							className="star-rating"
							role="img"
							aria-label="Rated 4 out of 5"
						>
							<span style={ { width: '80%' } }></span>
						</div>
						{ show_review_counter && (
							<a
								href="#reviews"
								className="woocommerce-review-link"
								rel="nofollow"
							>
								<span className="count">1</span>
								review
							</a>
						) }
					</div>
				) }
			</Wrapper>
		</div>
	);
};

export default Edit;
