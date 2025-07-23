import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountLogin } from '../../icons/myaccount-login.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountLogin,
} );
