import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	Button,
	__experimentalHeading as Heading,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const { label, cart_icon, cart_icon_pos } = attributes;

	const onMediaSelect = ( media ) => {
		const { id, url, type } = media;

		const icon = { value: id, url, type };

		setAttributes( { cart_icon: icon } );
	};

	const onRemoveImage = () => {
		setAttributes( { cart_icon: { value: '', url: '', type: '' } } );
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Label', 'shop-press' ) }
					value={ label }
					onChange={ ( value ) => setAttributes( { label: value } ) }
				/>

				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) => onMediaSelect( media ) }
						allowedTypes={ [ 'image' ] }
						value={ cart_icon?.value }
						render={ ( { open } ) => (
							<>
								<Heading>
									{ __( 'Icon', 'shop-press' ) }
								</Heading>
								<div style={ { display: 'flex', gap: '10px' } }>
									<Button variant="primary" onClick={ open }>
										{ __( 'Upload', 'shop-press' ) }
									</Button>

									{ cart_icon?.value && (
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

					{ cart_icon && (
						<div style={ { marginTop: '15px' } }>
							<img width={ 70 } src={ cart_icon?.url } />
						</div>
					) }

					{ cart_icon?.value && (
						<SelectControl
							label={ __( 'Icon Position', 'shop-press' ) }
							value={ cart_icon_pos }
							options={ [
								{ label: 'After', value: 'after' },
								{ label: 'Before', value: 'before' },
							] }
							onChange={ ( value ) =>
								setAttributes( { cart_icon_pos: value } )
							}
							__nextHasNoMarginBottom
						/>
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
					selector=".sp-onsale"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Badge', 'shop-press' ) }
					selector=".sp-onsale span.onsale"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon', 'shop-press' ) }
					selector=".sp-onsale span.onsale i"
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
	const { label } = attributes;
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
					block="shop-press/product-sale-badge"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
