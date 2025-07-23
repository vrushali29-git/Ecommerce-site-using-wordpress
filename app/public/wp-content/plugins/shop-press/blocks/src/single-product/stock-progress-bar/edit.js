import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { show_ordered, ordered_label, show_available, available_label } =
		attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Ordered', 'shop-press' ) }
					checked={ show_ordered }
					onChange={ () =>
						setAttributes( { show_ordered: ! show_ordered } )
					}
				/>
				{ show_ordered && (
					<TextControl
						label={ __( 'Ordered Label', 'shop-press' ) }
						value={ ordered_label }
						onChange={ ( value ) =>
							setAttributes( { ordered_label: value } )
						}
					/>
				) }

				<ToggleControl
					label={ __( 'Available', 'shop-press' ) }
					checked={ show_available }
					onChange={ () =>
						setAttributes( { show_available: ! show_available } )
					}
				/>
				{ show_available && (
					<TextControl
						label={ __( 'Available Label', 'shop-press' ) }
						value={ available_label }
						onChange={ ( value ) =>
							setAttributes( { available_label: value } )
						}
					/>
				) }
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-stock-progress-bar"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Ordered Container', 'shop-press' ) }
					selector=".sp-stock-progress-bar-labels .sp-stock-progress-bar-total-sales"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Ordered Label', 'shop-press' ) }
					selector=".sp-stock-progress-bar-total-sales span.sp-stock-progress-bar-labels-ordered"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Ordered Count', 'shop-press' ) }
					selector=".sp-stock-progress-bar-total-sales span.sp-stock-progress-bar-labels-ordered-count"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Items Available Container', 'shop-press' ) }
					selector=".sp-stock-progress-bar-stock-qty"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Items Available Label', 'shop-press' ) }
					selector=".sp-stock-progress-bar-stock-qty span.sp-stock-progress-bar-labels-available"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Items Available Count', 'shop-press' ) }
					selector=".sp-stock-progress-bar-stock-qty span.sp-stock-progress-bar-labels-available-count"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Bar', 'shop-press' ) }
					selector=".sp-stock-progress-bar-percent"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Percent', 'shop-press' ) }
					selector=".sp-stock-progress-bar-percent-sold"
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
	const { show_ordered, ordered_label, show_available, available_label } =
		attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>
			<Wrapper { ...props }>
				<div className="sp-stock-progress-bar">
					<div className="sp-stock-progress-bar-labels">
						{ show_ordered && (
							<div className="sp-stock-progress-bar-total-sales">
								<span className="sp-stock-progress-bar-labels-ordered-count">
									2
								</span>
								<span className="sp-stock-progress-bar-labels-ordered">
									{ ordered_label }
								</span>
							</div>
						) }
						{ show_available && (
							<div className="sp-stock-progress-bar-stock-qty">
								<span className="sp-stock-progress-bar-labels-available-count">
									3
								</span>
								<span className="sp-stock-progress-bar-labels-available">
									{ available_label }
								</span>
							</div>
						) }
					</div>
					<div className="sp-stock-progress-bar-percent">
						<div
							className="sp-stock-progress-bar-percent-sold"
							style={ { width: '40%' } }
						></div>
					</div>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
