import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as MyWishlist } from '../icons/my-wishlist.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: MyWishlist,
} );
