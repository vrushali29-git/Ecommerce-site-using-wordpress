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
					label={ __( 'Regular Price', 'shop-press' ) }
					selector=" .sp-price-wrapper p.price del"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Sale Price', 'shop-press' ) }
					selector=" .sp-price-wrapper p.price ins"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Symbol', 'shop-press' ) }
					selector=" .sp-price-wrapper .woocommerce-Price-amount.amount .woocommerce-Price-currencySymbol"
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
				<div className="sp-price-wrapper">
					<p className="price">
						<del aria-hidden="true">
							<span className="woocommerce-Price-amount amount">
								<bdi>
									<span className="woocommerce-Price-currencySymbol">
										$
									</span>
									55.00
								</bdi>
							</span>
						</del>
						<ins>
							<span className="woocommerce-Price-amount amount">
								<bdi>
									<span className="woocommerce-Price-currencySymbol">
										$
									</span>
									15.00
								</bdi>
							</span>
						</ins>
					</p>
				</div>
			</Wrapper>
		</div>
	);
}
