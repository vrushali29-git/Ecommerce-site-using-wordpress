import { registerBlockType } from '@wordpress/blocks';
import { ReactComponent as FlashSaleCountdown } from '../../icons/flash-sale-countdown.svg';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	icon: FlashSaleCountdown,
} );
