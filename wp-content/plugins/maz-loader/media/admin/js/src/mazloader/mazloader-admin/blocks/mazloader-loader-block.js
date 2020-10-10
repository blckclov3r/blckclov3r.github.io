class MZLDR_Loader_Block {
	constructor() {
		this.init();
	}

	init() {
		let i18n = wp.i18n;
		let mzldr_el = wp.element.createElement;
		let withSelect = wp.data.withSelect;
		let spinner = wp.components.Spinner;
		let selectControl = wp.components.SelectControl;
		let richText = wp.blockEditor.RichText;

		let loaders = null;

		wp.apiFetch( { path: '/maz-loader/loader/loaders' } ).then( function( _loaders ){
			loaders = _loaders;
		} );

		wp.blocks.registerBlockType('maz-loader/loader', {
			title: i18n.__('MAZ Loader', 'maz-loader'),
			description: i18n.__( 'Choose a Loader', 'maz-loader' ),
			icon: 'update',
			category: 'maz-loader',
			attributes: {
				content: {
					source: 'html',
					selector: 'p'
				},
				loader_id: {
					type: 'select'
				}
			},
			
			edit: withSelect(function (select) {
				return {
					loaders: loaders
				};
			})(function ( props ) {
				let loaders       = props.loaders,
					className     = props.className,
					attributes    = props.attributes,
					setAttributes = props.setAttributes,
					loader_id     = props.attributes.loader_id,
					content       = props.attributes.content;

				function get_option( loaders ) {

					let option = [];

					loaders.map( function( loader ) {
						option.push(
							{
								label: loader.title,
								value: loader.id
							}
						);
					});

					return option;
				}

				function mzldrShortcode( value ) {

					let shortcode = '';

					if ( value !== undefined ) {
						shortcode = '[mzldr loader_id="' + value + '"]';
					}

					return shortcode;
				}

				if ( ! loaders ) {
					return mzldr_el(
						'p',
						{
							className: className
						},
						mzldr_el(
							spinner,
							null
						),
						i18n.__( 'Loading MAZ Loaders', 'maz-loader' )
					);
				}

				if ( 0 === loaders.length ) {
					return mzldr_el(
						'p',
						null,
						i18n.__( 'No Loaders found', 'maz-loader' )
					);
				}

				if ( loader_id === undefined ) {
					props.setAttributes({ loader_id: loaders[0]['id'] });
					let shortcode = mzldrShortcode(loaders[0]['id']);
					props.setAttributes( { content: shortcode } );
				}

				let get_loaders = get_option( loaders );

				return mzldr_el(
					'div',
					{
						className: className
					},
					mzldr_el(
						selectControl,
						{
							label: i18n.__( 'Select MAZ Loader', 'maz-loader' ),
							className: "",
							type: 'number',
							value: loader_id,
							options: get_loaders,
							onChange: function onChange( value ) {
								props.setAttributes({ loader_id: value });
								let shortcode = mzldrShortcode(value);
								props.setAttributes( { content: shortcode } );
							}
						}
					)
				);
			}
			),
			save: function save( props ) {
				return mzldr_el(
					richText.Content,
					{
						tagName: 'p',
						value: props.attributes.content
					}
				);
			}
		});
	}
}
new MZLDR_Loader_Block();