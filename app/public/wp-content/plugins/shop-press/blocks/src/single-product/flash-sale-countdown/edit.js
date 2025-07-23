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
					selector=".fs-countdown-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Timer Title', 'shop-press' ) }
					selector=".fs-countdown-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Item Wrapper', 'shop-press' ) }
					selector=".fs-countdown ul li"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Numbers', 'shop-press' ) }
					selector=".fs-countdown ul li div"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Labels', 'shop-press' ) }
					selector=".fs-countdown ul li span"
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
					block="shop-press/product-flash-sale-countdown"
					attributes={ attributes }
				/>
			</Wrapper>
		</>
	);
};

export default Edit;
