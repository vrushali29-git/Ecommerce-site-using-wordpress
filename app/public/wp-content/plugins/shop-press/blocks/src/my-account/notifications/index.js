import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountNotifications } from '../../icons/myaccount-notifications.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountNotifications,
} );
