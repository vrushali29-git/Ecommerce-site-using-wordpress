import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes } ) => {
	// const { tag } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ true }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-sharing"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Social Container', 'shop-press' ) }
					selector=".sp-sharing .sp-sharing-button"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Share Icon Label', 'shop-press' ) }
					selector=".sp-sharing .sp-sharing-button span.sp-sharing-label"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Share Icon', 'shop-press' ) }
					selector=".sp-sharing .sp-sharing-button .sp-sharing-icon path"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Sharing Items Wrapper', 'shop-press' ) }
					selector=".sp-sharing ul"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Sharing Item', 'shop-press' ) }
					selector="ul li.sp-sharing-item "
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Item Title', 'shop-press' ) }
					selector="li.sp-sharing-item a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Item Icon', 'shop-press' ) }
					selector="li.sp-sharing-item a i"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

const Edit = ( { attributes, setAttributes } ) => {
	return (
		<>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
			<Wrapper { ...props }>
				<ServerSideRender
					block="shop-press/product-sharing"
					attributes={ attributes }
				/>
			</Wrapper>
		</>
	);
};

export default Edit;
