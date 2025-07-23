import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ThankYouCustomerDetails } from '../../icons/thank-you-customer-details.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ThankYouCustomerDetails,
} );
