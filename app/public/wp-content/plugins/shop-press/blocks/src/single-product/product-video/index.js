import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as ProductVideo } from '../../icons/product-video.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: ProductVideo,
} );
