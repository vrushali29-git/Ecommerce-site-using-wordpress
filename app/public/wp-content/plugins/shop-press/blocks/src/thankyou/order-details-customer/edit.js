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
					selector=".woocommerce-customer-details"
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
					label={ __( 'Address', 'shop-press' ) }
					selector="address"
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
				<section className="woocommerce-customer-details">
					<div className="sp-thankyou-order-details-customer">
						<section className="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
							<div className="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">
								<h2 className="woocommerce-column__title">
									Billing address
								</h2>

								<address>
									United States (US) Minor Outlying Islands
									<p className="woocommerce-customer-details--phone">
										'999-99-99'
									</p>
									<p className="woocommerce-customer-details--email">
										sample@yourdomain.com
									</p>
								</address>
							</div>

							<div className="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
								<h2 className="woocommerce-column__title">
									Shipping address
								</h2>
								<address>
									United States (US) Minor Outlying Islands
									<p className="woocommerce-customer-details--phone">
										'999-99-99'
									</p>
								</address>
							</div>
						</section>
					</div>
				</section>
			</Wrapper>
		</div>
	);
}
