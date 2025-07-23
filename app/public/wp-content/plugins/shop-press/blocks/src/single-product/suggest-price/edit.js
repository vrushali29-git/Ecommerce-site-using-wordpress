import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import Styler from '../../styler';
import Wrapper from '../../wrapper';

const Inspector = ( { attributes, setAttributes, clientId } ) => {
	const {
		btn_text,
		email_address,
		name_placeholder,
		email_placeholder,
		price_placeholder,
		message_placeholder,
		submit_btn_text,
	} = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'General', 'shop-press' ) }
				initialOpen={ true }
			>
				<TextControl
					label={ __( 'Text', 'shop-press' ) }
					value={ btn_text }
					onChange={ ( value ) =>
						setAttributes( { btn_text: value } )
					}
				/>
				<TextControl
					label={ __( 'Email Address', 'shop-press' ) }
					description={ __(
						'The email will sent to this address.',
						'shop-press'
					) }
					value={ email_address }
					onChange={ ( value ) =>
						setAttributes( { email_address: value } )
					}
				/>
				<TextControl
					label={ __( 'Name Placeholder', 'shop-press' ) }
					value={ name_placeholder }
					onChange={ ( value ) =>
						setAttributes( { name_placeholder: value } )
					}
				/>
				<TextControl
					label={ __( 'Email Placeholder', 'shop-press' ) }
					value={ email_placeholder }
					onChange={ ( value ) =>
						setAttributes( { email_placeholder: value } )
					}
				/>
				<TextControl
					label={ __( 'Price Placeholder', 'shop-press' ) }
					value={ price_placeholder }
					onChange={ ( value ) =>
						setAttributes( { price_placeholder: value } )
					}
				/>
				<TextControl
					label={ __( 'Message Placeholder', 'shop-press' ) }
					value={ message_placeholder }
					onChange={ ( value ) =>
						setAttributes( { message_placeholder: value } )
					}
				/>
				<TextControl
					label={ __( 'Submit Text', 'shop-press' ) }
					value={ submit_btn_text }
					onChange={ ( value ) =>
						setAttributes( { submit_btn_text: value } )
					}
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Styles', 'shop-press' ) }
				initialOpen={ false }
			>
				<Styler
					clientId={ clientId }
					label={ __( 'Wrapper', 'shop-press' ) }
					selector=".sp-suggest-price"
					wrapper={ `.${ attributes[ 'wrapperID' ] }` }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<Styler
					clientId={ clientId }
					label={ __( 'Link', 'shop-press' ) }
					selector=".sp-suggest-price a.button"
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
	const { btn_text } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector
				attributes={ attributes }
				setAttributes={ setAttributes }
				clientId={ clientId }
			/>

			<Wrapper { ...props }>
				<div className="sp-suggest-price">
					<a href="#" className="button">
						{ btn_text }
					</a>
				</div>
			</Wrapper>
		</div>
	);
};

export default Edit;
