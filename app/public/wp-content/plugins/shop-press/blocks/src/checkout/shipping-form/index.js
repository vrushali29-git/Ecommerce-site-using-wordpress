import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ShippingForm } from '../../icons/shipping-form.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ShippingForm,
} );
