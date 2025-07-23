import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import Styler from '../styler';
import Wrapper from '../wrapper';

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
					selector=".sp-my-wishlist"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Wrapper', 'shop-press' ) }
					selector=".sp-my-wishlist-header"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Icon', 'shop-press' ) }
					selector=".sp-my-wishlist-header .my-wishlist-title svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Title', 'shop-press' ) }
					selector=".sp-my-wishlist-header .my-wishlist-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Share Status', 'shop-press' ) }
					selector=".sp-my-wishlist-header .sp-wishlist-info .sp-wishlist-share-status"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Created Date', 'shop-press' ) }
					selector=".sp-my-wishlist-header .sp-wishlist-info .sp-wishlist-created-date"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Avatar image', 'shop-press' ) }
					selector=".sp-my-wishlist-header .sp-wishlist-author .sp-wishlist-author-avatar"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Header Avatar Name', 'shop-press' ) }
					selector=".sp-my-wishlist-header .sp-wishlist-author .sp-wishlist-author-name"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'table', 'shop-press' ) }
					selector=".sp-my-wishlist table"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'thead', 'shop-press' ) }
					selector=".sp-my-wishlist thead"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tr', 'shop-press' ) }
					selector=".sp-my-wishlist tr"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'th', 'shop-press' ) }
					selector=".sp-my-wishlist th"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'td', 'shop-press' ) }
					selector=".sp-my-wishlist td"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tbody', 'shop-press' ) }
					selector=".sp-my-wishlist tbody"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tfoot', 'shop-press' ) }
					selector=".sp-my-wishlist tfoot"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Title', 'shop-press' ) }
					selector=".sp-product .sp-product-row .sp-product-col .sp-content .sp-prod-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Link', 'shop-press' ) }
					selector=".sp-product .sp-product-row .sp-product-col .sp-content .sp-prod-title a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Thumbnail', 'shop-press' ) }
					selector=".sp-my-wishlist .my-wishlist-table tbody tr.sp-wishlist-list td.sp-product .sp-product-row .sp-thumb img"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Price', 'shop-press' ) }
					selector=".sp-product-price span.woocommerce-Price-amount.amount bdi"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Price Symbol', 'shop-press' ) }
					selector=".sp-product-price span.woocommerce-Price-currencySymbol"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Product Stock', 'shop-press' ) }
					selector=".sp-my-wishlist table tbody .sp-product-stock"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Add To Cart Button', 'shop-press' ) }
					selector=".sp-my-wishlist table tbody .sp-product-addtocart .button"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Table Add To Cart Button Icon',
						'shop-press'
					) }
					selector=".sp-my-wishlist table tbody .sp-product-addtocart .button i"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Table Add To Cart Button SVG Icon',
						'shop-press'
					) }
					selector=".sp-my-wishlist table tbody .sp-product-addtocart .button svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Remove Button Icon', 'shop-press' ) }
					selector=".sp-rmf-wishlist"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Table Remove Button SVG Icon', 'shop-press' ) }
					selector=".sp-product-remove-item svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Products', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Item', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Thumbnail Wrapper', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-thumbnail"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Thumbnail', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-thumbnail img"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Title', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Title Link', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-title a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Price', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-price"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Regular Price', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-price del"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Sale Price', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-price ins"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Rating Wrapper', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-rating"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Rating Star', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-rating svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Added Date', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-date-added"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Date Icon', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-date-added svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Product Stock', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-stock"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid In Stock', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-stock.instock"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Out Of Stock', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-stock.outofstock"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Remove Button', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-remove-item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Remove Button Icon', 'shop-press' ) }
					selector=".sp-my-wishlist .sp-my-wishlist-grid .sp-my-wishlist-item .sp-my-wishlist-item-remove-item i"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Pagination Wrapper', 'shop-press' ) }
					selector=".sp-pagination"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Pagination Buttons', 'shop-press' ) }
					selector=".sp-my-wishlist-footer .sp-pagination .page-numbers li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Pagination Page Numbers', 'shop-press' ) }
					selector=".sp-my-wishlist-footer .sp-pagination .page-numbers li .page-numbers"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __(
						'Grid Pagination Current Page Numbers',
						'shop-press'
					) }
					selector=".sp-my-wishlist-footer .sp-pagination .page-numbers li .page-numbers.current"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Pagination Prev Page', 'shop-press' ) }
					selector=".sp-my-wishlist-footer .sp-pagination .page-numbers li .page-numbers.prev"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Grid Pagination Next Page', 'shop-press' ) }
					selector=".sp-my-wishlist-footer .sp-pagination .page-numbers li .page-numbers.next"
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
					block="shop-press/my-wishlist"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
