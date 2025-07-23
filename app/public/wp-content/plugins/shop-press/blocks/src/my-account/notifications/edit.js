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
				initialOpen={ true }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-notifications-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Message item', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon Wrapper', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-icon"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Icon', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-icon svg"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Content Wrapper', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Title', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-title"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Content', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'View Order Link', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content a"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Date Wrapper', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-date-wrap"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Date', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-date-wrap .sp-notification-item-date"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Show More Button', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-show-more"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Show More Button Icon', 'shop-press' ) }
					selector=".sp-notifications-wrap .sp-notifications-items .sp-notification-item .sp-notification-item-content-show-more svg"
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
					block="shop-press/my-account-notifications"
					attributes={ attributes }
				/>
			</Wrapper>
		</div>
	);
};

export default Edit;
