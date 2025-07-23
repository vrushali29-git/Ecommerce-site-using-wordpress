import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import {
	PanelBody,
	SelectControl,
	TextControl,
	Button,
	__experimentalHeading as Heading,
} from '@wordpress/components';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { button_style, custom_text, custom_link, btn_icon } = attributes;

	const onMediaSelect = ( media ) => {
		const { id, url, type } = media;

		const icon = { value: id, url, type };

		setAttributes( { btn_icon: icon } );
	};

	const onRemoveImage = () => {
		setAttributes( { btn_icon: { value: '', url: '', type: '' } } );
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<SelectControl
					label={ __( 'Button Style', 'shop-press' ) }
					value={ button_style }
					options={ [
						{ label: __( 'Text', 'shop-press' ), value: 'text' },
						{ label: __( 'Icon', 'shop-press' ), value: 'icon' },
						{
							label: __( 'Text Icon', 'shop-press' ),
							value: 'button_icon',
						},
						{
							label: __( 'Icon Text', 'shop-press' ),
							value: 'icon_button',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { button_style: value } )
					}
					__nextHasNoMarginBottom
				/>
				<TextControl
					label={ __( 'Custom Text', 'shop-press' ) }
					value={ custom_text }
					onChange={ ( value ) =>
						setAttributes( { custom_text: value } )
					}
				/>
				<TextControl
					label={ __( 'Custom Link', 'shop-press' ) }
					value={ custom_link }
					onChange={ ( value ) =>
						setAttributes( { custom_link: value } )
					}
				/>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) => onMediaSelect( media ) }
						allowedTypes={ [ 'image' ] }
						value={ btn_icon?.value }
						render={ ( { open } ) => (
							<>
								<Heading>
									{ __( 'Icon', 'shop-press' ) }
								</Heading>
								<div style={ { display: 'flex', gap: '10px' } }>
									<Button variant="primary" onClick={ open }>
										{ __( 'Upload', 'shop-press' ) }
									</Button>

									{ btn_icon?.value && (
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

					{ btn_icon && (
						<div style={ { marginTop: '15px' } }>
							<img width={ 70 } src={ btn_icon?.url } />
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
					selector=".sp-return-to-shop"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon', 'shop-press' ) }
					selector=".sp-return-to-shop .sp-icon"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Button', 'shop-press' ) }
					selector=".sp-return-to-shop .button"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Title', 'shop-press' ) }
					selector=".sp-return-to-shop a"
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
					block="shop-press/cart-return-to-shop"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
