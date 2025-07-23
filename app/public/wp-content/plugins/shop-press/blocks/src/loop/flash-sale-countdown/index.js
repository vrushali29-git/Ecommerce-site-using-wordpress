import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as FlashSaleCouuntdownLoop } from '../../icons/flash-sale-countdown-loop.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: FlashSaleCouuntdownLoop,
} );
