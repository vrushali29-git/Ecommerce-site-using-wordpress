import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	SelectControl,
	Button,
	__experimentalHeading as Heading,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { show_label, overlay, icon, icon_pos } = attributes;

	const onMediaSelect = ( media ) => {
		const { id, url, type } = media;

		const icon = { value: id, url, type };

		setAttributes( { icon: icon } );
	};

	const onRemoveImage = () => {
		setAttributes( { icon: { value: '', url: '', type: '' } } );
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Show Label', 'shop-press' ) }
					checked={ show_label }
					onChange={ () =>
						setAttributes( {
							show_label: ! show_label,
						} )
					}
				/>

				<ToggleControl
					label={ __( 'Floating Position', 'shop-press' ) }
					checked={ overlay }
					onChange={ () =>
						setAttributes( {
							overlay: ! overlay,
						} )
					}
				/>

				<SelectControl
					label={ __( 'Icon Position', 'shop-press' ) }
					value={ icon_pos }
					options={ [
						{ label: 'Left', value: 'left' },
						{ label: 'Right', value: 'right' },
					] }
					onChange={ ( value ) =>
						setAttributes( { icon_pos: value } )
					}
					__nextHasNoMarginBottom
				/>

				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) => onMediaSelect( media ) }
						allowedTypes={ [ 'image' ] }
						value={ icon?.value }
						render={ ( { open } ) => (
							<>
								<Heading>
									{ __( 'Icon', 'shop-press' ) }
								</Heading>
								<div style={ { display: 'flex', gap: '10px' } }>
									<Button variant="primary" onClick={ open }>
										{ __( 'Upload', 'shop-press' ) }
									</Button>

									{ icon?.value && (
										<Button
											variant="secondary"
											onClick={ onRemoveImage }
										>
											{ __( 'Remove', 'shop-press' ) }
										</Button>
									) }
								</div>
							</>
						) }
					/>

					{ icon && (
						<div style={ { marginTop: '15px' } }>
							<img width={ 70 } src={ icon?.url } />
						</div>
					) }
				</MediaUploadCheck>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-product-compare"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Button', 'shop-press' ) }
					selector=".sp-product-compare .sp-compare-button-loop"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon Wrapper', 'shop-press' ) }
					selector=".sp-product-compare .sp-compare-icon"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon', 'shop-press' ) }
					selector=".sp-product-compare .sp-compare-icon i.sp-icon"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Active Icon', 'shop-press' ) }
					selector=".sp-product-compare[data-status='yes'] .sp-compare-icon i.sp-icon"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Label', 'shop-press' ) }
					selector=".sp-product-compare .sp-compare-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tooltip', 'shop-press' ) }
					selector=".sp-product-compare.overlay .sp-compare-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Tooltip Arrow', 'shop-press' ) }
					selector=".sp-product-compare.overlay span:before"
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
					block="shop-press/loop-compare"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
