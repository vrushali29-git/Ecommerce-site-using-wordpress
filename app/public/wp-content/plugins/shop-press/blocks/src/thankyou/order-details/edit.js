import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
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
					selector=".woocommerce-order-details"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Title', 'shop-press' ) }
					selector="h2"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'table', 'shop-press' ) }
					selector=".order_details"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'thead', 'shop-press' ) }
					selector=".order_details thead"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tr', 'shop-press' ) }
					selector=".order_details tr"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'th', 'shop-press' ) }
					selector=".order_details th"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'td', 'shop-press' ) }
					selector=".order_details td"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tbody', 'shop-press' ) }
					selector=".order_details tbody"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'tfoot', 'shop-press' ) }
					selector=".order_details tfoot"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default function Edit( props ) {
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
				<div className="sp-thankyou-order-details">
					<section className="woocommerce-order-details">
						<h2 className="woocommerce-order-details__title">
							Order details
						</h2>
						<table className="woocommerce-table woocommerce-table--order-details shop_table order_details">
							<thead>
								<tr>
									<th className="woocommerce-table__product-name product-name">
										Product
									</th>
									<th className="woocommerce-table__product-table product-total">
										Total
									</th>
								</tr>
							</thead>
							<tbody>
								<tr className="woocommerce-table__line-item order_item">
									<td className="woocommerce-table__product-name product-name">
										<a href="#">T-Shirt</a>
										<strong className="product-quantity">
											×&nbsp;1
										</strong>
									</td>
									<td className="woocommerce-table__product-total product-total">
										<span className="woocommerce-Price-amount amount">
											<bdi>
												<span className="woocommerce-Price-currencySymbol">
													$
												</span>
												200.00
											</bdi>
										</span>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th scope="row">Subtotal:</th>
									<td>
										<span className="woocommerce-Price-amount amount">
											<span className="woocommerce-Price-currencySymbol">
												$
											</span>
											200.00
										</span>
									</td>
								</tr>
								<tr>
									<th scope="row">Discount:</th>
									<td>
										-
										<span className="woocommerce-Price-amount amount">
											<span className="woocommerce-Price-currencySymbol">
												$
											</span>
											200.00
										</span>
									</td>
								</tr>
								<tr>
									<th scope="row">Shipping:</th>
									<td>Flat rate</td>
								</tr>
								<tr>
									<th scope="row">Total:</th>
									<td>
										<span className="woocommerce-Price-amount amount">
											<span className="woocommerce-Price-currencySymbol">
												$
											</span>
											0.00
										</span>
									</td>
								</tr>
							</tfoot>
						</table>
					</section>
				</div>
			</Wrapper>
		</div>
	);
}
