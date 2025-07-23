import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { PanelBody } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			></PanelBody>
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
					block="shop-press/checkout-login-form"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
