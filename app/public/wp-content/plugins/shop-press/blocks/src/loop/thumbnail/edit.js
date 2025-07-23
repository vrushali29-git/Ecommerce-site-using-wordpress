import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { thumbnail_type, show_arrows, show_nav } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Thumbnail Type', 'shop-press' ) }
					value={ thumbnail_type }
					options={ [
						{ label: 'Single', value: 'single' },
						{
							label: 'Change image on hover',
							value: 'change_image_on_hover',
						},
						{ label: 'Slider', value: 'slider' },
					] }
					onChange={ ( value ) =>
						setAttributes( { thumbnail_type: value } )
					}
					__nextHasNoMarginBottom
				/>

				{ thumbnail_type === 'slider' && (
					<>
						<ToggleControl
							label={ __( 'Arrows', 'shop-press' ) }
							checked={ show_arrows }
							onChange={ () =>
								setAttributes( { show_arrows: ! show_arrows } )
							}
						/>

						<ToggleControl
							label={ __( 'Navigation', 'shop-press' ) }
							checked={ show_nav }
							onChange={ () =>
								setAttributes( { show_nav: ! show_nav } )
							}
						/>
					</>
				) }
			</PanelBody>
			<PanelBody title={ __( 'Styles', 'shop-press' ) }>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-product-thumbnail"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Image Wrapper', 'shop-press' ) }
					selector=".sp-product-thumbnail a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Image', 'shop-press' ) }
					selector=".sp-product-thumbnail a img"
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
					block="shop-press/loop-thumbnail"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
