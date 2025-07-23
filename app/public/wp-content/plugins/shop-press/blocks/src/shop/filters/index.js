import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ShopFilter } from '../../icons/shop-filter.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ShopFilter,
} );
