import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountOrders } from '../../icons/myaccount-orders.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountOrders,
} );
