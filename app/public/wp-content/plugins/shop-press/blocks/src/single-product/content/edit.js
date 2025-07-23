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
					selector=".sp-product-content-wrapper"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Content', 'shop-press' ) }
					selector=".sp-product-content"
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
				<div className="sp-product-content-wrapper">
					<p className="sp-product-content">
						Pellentesque habitant morbi tristique senectus et netus
						et malesuada fames ac turpis egestas. Vestibulum tortor
						quam, feugiat vitae, ultricies eget, tempor sit amet,
						ante. Donec eu libero sit amet quam egestas semper.
						Aenean ultricies mi vitae est. Mauris placerat eleifend
						leo.
					</p>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
