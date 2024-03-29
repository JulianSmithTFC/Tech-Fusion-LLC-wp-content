/**
 * WordPress dependencies
 */
import { dispatch } from '@wordpress/data';
import { __, sprintf } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import apiFetch from '../../api-fetch';

class LockoutController {
	#details = {};
	#fetching = {};
	#releasing = {};

	getDetails( url ) {
		if ( this.#details[ url ] ) {
			return Promise.resolve( this.#details[ url ] );
		}

		if ( ! this.#fetching[ url ] ) {
			this.#fetching[ url ] = apiFetch( { url } ).then( ( response ) => {
				this.#details[ url ] = response.detail;
				delete this.#fetching[ url ];

				return response.detail;
			} );
		}

		return this.#fetching[ url ];
	}

	isFetching( url ) {
		return !! this.#fetching[ url ];
	}

	release( url ) {
		if ( ! this.#releasing[ url ] ) {
			this.#releasing[ url ] = apiFetch( {
				url,
				method: 'DELETE',
			} ).then( ( response ) => {
				delete this.#releasing[ url ];

				const id = `release-lockout-${ url }`;

				setTimeout( () => dispatch( 'core/notices' ).removeNotice( id ), 5000 );
				dispatch( 'core/notices' ).createNotice( 'success', __( 'Lockout Released', 'ithemes-security-pro' ), { id } );

				return response;
			} ).catch( ( e ) => {
				delete this.#releasing[ url ];

				dispatch( 'core/notices' ).createNotice(
					'error',
					sprintf( __( 'Error when creating dashboard: %s', 'ithemes-security-pro' ), e.message )
				);
			} );
		}

		return this.#releasing[ url ];
	}

	isReleasing( url ) {
		return !! this.#releasing[ url ];
	}
}

const controller = new LockoutController();

export default controller;
