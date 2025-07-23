import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as WishlistButton } from '../../icons/wishlist-button.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: WishlistButton,
} );
