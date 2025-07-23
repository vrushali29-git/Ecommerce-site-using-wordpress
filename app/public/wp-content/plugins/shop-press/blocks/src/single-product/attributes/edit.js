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
					selector=".sp-attributes-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Container', 'shop-press' ) }
					selector=".sp-attributes-wrapper .woocommerce-product-attributes"
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
					label={ __( 'Value', 'shop-press' ) }
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
				<div className="sp-attributes-wrapper">
					<table className="woocommerce-product-attributes shop_attributes">
						<tbody>
							<tr className="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_color">
								<th className="woocommerce-product-attributes-item__label">
									Color
								</th>
								<td className="woocommerce-product-attributes-item__value">
									<p>Blue, Gray, Green, Red, Yellow</p>
								</td>
							</tr>
							<tr className="woocommerce-product-attributes-item woocommerce-product-attributes-item--attribute_pa_size">
								<th className="woocommerce-product-attributes-item__label">
									Size
								</th>
								<td className="woocommerce-product-attributes-item__value">
									<p>Large, Medium, Small</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
