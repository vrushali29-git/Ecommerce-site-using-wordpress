import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ThankYouOrderDetails } from '../../icons/thank-you-order-details.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ThankYouOrderDetails,
} );
