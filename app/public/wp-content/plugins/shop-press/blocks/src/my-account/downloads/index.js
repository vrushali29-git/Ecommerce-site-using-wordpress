import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyaccountDownloads } from '../../icons/myaccount-downloads.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyaccountDownloads,
} );
