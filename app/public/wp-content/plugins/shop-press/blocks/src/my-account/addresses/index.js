import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountAddresses } from '../../icons/myaccount-addresses.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountAddresses,
} );
