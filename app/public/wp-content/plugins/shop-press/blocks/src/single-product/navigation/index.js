import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductNavigation } from '../../icons/product-navigation.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductNavigation,
} );
