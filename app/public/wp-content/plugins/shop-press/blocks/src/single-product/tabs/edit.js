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
					selector=".sp-tabs"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tabs Wrapper', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tabs Item', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tabs Title', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Active Tab Item', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li.active"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Active Tab Item link', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper ul.tabs.wc-tabs li.active a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Accordion Wrapper', 'shop-press' ) }
					selector=".sp-accordions-container"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Accordion Item', 'shop-press' ) }
					selector=".sp-accordions-container .sp-accordion-item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Accordion Item Title', 'shop-press' ) }
					selector=".sp-accordions-container .sp-accordion-item .sp-accordion-item-header"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Accordion Active Item', 'shop-press' ) }
					selector=".sp-accordions-container .sp-accordion-item.open"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Accordion Active Item Title', 'shop-press' ) }
					selector=".sp-accordions-container .sp-accordion-item.open .sp-accordion-item-header"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Accordion Item Content Wrapper',
						'shop-press'
					) }
					selector=".sp-accordions-container .sp-accordion-item .sp-accordion-item-content"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tabs Panel', 'shop-press' ) }
					selector=".sp-tabs.woocommerce-tabs.wc-tabs-wrapper .woocommerce-Tabs-panel"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Description Content', 'shop-press' ) }
					selector=".woocommerce-tabs .woocommerce-Tabs-panel.woocommerce-Tabs-panel--description p"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Attributes Container', 'shop-press' ) }
					selector=".woocommerce-Tabs-panel.woocommerce-Tabs-panel--additional_information .woocommerce-product-attributes"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Body', 'shop-press' ) }
					selector=".woocommerce-product-attributes tbody"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Row', 'shop-press' ) }
					selector=".woocommerce-product-attributes tbody tr.woocommerce-product-attributes-item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Header', 'shop-press' ) }
					selector="tr.woocommerce-product-attributes-item th.woocommerce-product-attributes-item__label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Value Cell', 'shop-press' ) }
					selector="tr.woocommerce-product-attributes-item td.woocommerce-product-attributes-item__value"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Value Content', 'shop-press' ) }
					selector="td.woocommerce-product-attributes-item__value p"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Link', 'shop-press' ) }
					selector="td.woocommerce-product-attributes-item__value p a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Reviews Wrapper', 'shop-press' ) }
					selector=".woocommerce-Tabs-panel.woocommerce-Tabs-panel--reviews #reviews.woocommerce-Reviews"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comments Summary Wrapper', 'shop-press' ) }
					selector="#reviews.woocommerce-Reviews .kata-review-summery"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Overall Rating Wrapper', 'shop-press' ) }
					selector=".kata-review-summery .overall-rating-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Average Rating', 'shop-press' ) }
					selector=".overall-rating-wrap div.average-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Average Stars Wrapper', 'shop-press' ) }
					selector=".overall-rating-wrap .star-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Average Star', 'shop-press' ) }
					selector=".star-rating span"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Reviews Count', 'shop-press' ) }
					selector=".overall-rating-wrap .reviews-count"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Review Button Wrapper', 'shop-press' ) }
					selector=".overall-rating-wrap .write-a-review"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Review Button', 'shop-press' ) }
					selector=".write-a-review .button"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Rating Summary Wrapper', 'shop-press' ) }
					selector=".kata-review-summery .rating-summary-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Rating Summary Item', 'shop-press' ) }
					selector=".rating-summary-wrap div.item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Item Rate Count', 'shop-press' ) }
					selector="div.item .rate-count"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comments Container', 'shop-press' ) }
					selector="#reviews #comments"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Review Title', 'shop-press' ) }
					selector="#comments h2.woocommerce-Reviews-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Product Title', 'shop-press' ) }
					selector="h2.woocommerce-Reviews-title span"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comments List Wrapper', 'shop-press' ) }
					selector=".woocommerce #reviews #comments ol.commentlist"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment', 'shop-press' ) }
					selector=".woocommerce #reviews #comments ol.commentlist li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Container', 'shop-press' ) }
					selector=".woocommerce #reviews #comments ol.commentlist li .comment_container"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Image', 'shop-press' ) }
					selector=".woocommerce #reviews #comments ol.commentlist .comment_container img.avatar"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Text', 'shop-press' ) }
					selector=".woocommerce #reviews #comments ol.commentlist .comment_container .comment-text"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Meta Wrapper', 'shop-press' ) }
					selector=".comment-text .meta"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Author', 'shop-press' ) }
					selector="p.meta strong.woocommerce-review__author"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Date', 'shop-press' ) }
					selector="p.meta time.woocommerce-review__published-date"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Item Description', 'shop-press' ) }
					selector=".description p"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form Wrapper', 'shop-press' ) }
					selector="#reviews.woocommerce-Reviews #review_form_wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Reviews Container', 'shop-press' ) }
					selector="#review_form_wrapper #review_form"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form', 'shop-press' ) }
					selector="#review_form #respond.comment-respond"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Reply Title', 'shop-press' ) }
					selector="#respond.comment-respond span.comment-reply-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Form', 'shop-press' ) }
					selector="#respond.comment-respond form.comment-form"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Form Rating', 'shop-press' ) }
					selector="form.comment-form .comment-form-rating .rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Form Rating Label', 'shop-press' ) }
					selector="form.comment-form .comment-form-rating label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Form Text Area Wrapper', 'shop-press' ) }
					selector="form.comment-form p.comment-form-comment"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Form Label', 'shop-press' ) }
					selector="p.comment-form-comment label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Comment Textarea', 'shop-press' ) }
					selector=".woocommerce #reviews #review_form_wrapper #review_form #respond .comment-form .comment-form-comment textarea"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Submit Button Wrapper', 'shop-press' ) }
					selector=".comment-form p.form-submit"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Submit Button', 'shop-press' ) }
					selector=".woocommerce #reviews #review_form_wrapper #review_form #respond .comment-form p.form-submit input[type='submit']"
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
					block="shop-press/product-tabs"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
