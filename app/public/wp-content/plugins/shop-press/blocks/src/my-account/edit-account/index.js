import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountEditAccount } from '../../icons/myaccount-edit-account.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountEditAccount,
} );
