import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as Payment } from '../../icons/payment.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: Payment,
} );
