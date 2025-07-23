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
					label={ __( 'Order Reviews Wrapper', 'shop-press' ) }
					selector=".woocommerce-order-overview"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Message', 'shop-press' ) }
					selector=".woocommerce-notice"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Order Reviews Items', 'shop-press' ) }
					selector=".woocommerce-order-overview li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Order Reviews Item value', 'shop-press' ) }
					selector=".woocommerce-order-overview li strong"
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
				<div className="sp-thankyou-order-review">
					<p className="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
						Thank you. Your order has been received.
					</p>

					<ul className="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
						<li className="woocommerce-order-overview__order order">
							Order number:
							<strong>562</strong>
						</li>

						<li className="woocommerce-order-overview__date date">
							Date:
							<strong>February 26, 2024</strong>
						</li>

						<li className="woocommerce-order-overview__email email">
							Email:
							<strong>sample@yourdomain.com</strong>
						</li>

						<li className="woocommerce-order-overview__total total">
							Total:
							<strong>$49.50</strong>
						</li>

						<li className="woocommerce-order-overview__payment-method method">
							Payment method:
							<strong>Direct bank transfer</strong>
						</li>
					</ul>
				</div>
			</Wrapper>
		</div>
	);
}
