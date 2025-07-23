import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as FormBilling } from '../../icons/form-billing.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: FormBilling,
} );
