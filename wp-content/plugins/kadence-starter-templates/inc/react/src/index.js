/**
 * Internal dependencies
 */
// import HelpTab from './help';
// import ProSettings from './pro-extension';
// import RecommendedTab from './recomended';
// import StarterTab from './starter';
// import Sidebar from './sidebar';
// import CustomizerLinks from './customizer';
// import Notices from './notices';
import map from 'lodash/map';
import LazyLoad from 'react-lazy-load';
/**
 * WordPress dependencies
 */
const { __, sprintf } = wp.i18n;
const { Fragment, Component, render } = wp.element;
const { Modal, Spinner, ButtonGroup, Button, ExternalLink } = wp.components;
const { apiFetch } = wp;

class KadenceImporter extends Component {
	constructor() {
		super( ...arguments );
		this.runAjax = this.runAjax.bind( this );
		this.runPluginInstall = this.runPluginInstall.bind( this );
		this.focusMode = this.focusMode.bind( this );
		this.state = {
			category: 'all',
			activeTemplate: '',
			colorPalette: '',
			search: null,
			isFetching: false,
			isImporting: false,
			progress: '',
			focusMode: false,
			finished: false,
			templates: ( kadenceStarterParams.templates ? kadenceStarterParams.templates : [] ),
			palettes: ( kadenceStarterParams.palettes ? kadenceStarterParams.palettes : [] ),
		};
		apiFetch.setFetchHandler( ( options ) => {
			const { url, path, data, method } = options;
		 
			return axios( {
				url: url || path,
				method,
				data,
			} );
		} );
	}
	capitalizeFirstLetter( string ) {
		return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
	}
	focusMode( template_id ) {
		this.setState( { activeTemplate: template_id, focusMode: true } )
	}
	runPluginInstall( selected ) {
		this.setState( { progress: 'plugins', isFetching: true } );
		var data = new FormData();
		data.append( 'action', 'kadence_import_install_plugins' );
		data.append( 'security', kadenceStarterParams.ajax_nonce );
		data.append( 'selected', selected );
		this.runAjax( data );
	}
	runAjax( data ) {
		var control = this;
		jQuery.ajax({
			method:      'POST',
			url:         kadenceStarterParams.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
		})
		.done( function( response, status, stately ) {
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				control.state.progress = 'contentNew';
				control.runAjax( data );
			} else if ( 'undefined' !== typeof response.status && 'customizerAJAX' === response.status ) {
				control.setState( { progress: 'customizer' } );
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'kadence_import_customizer_data' );
				newData.append( 'security', kadenceStarterParams.ajax_nonce );
				newData.append( 'wp_customize', 'on' );

				control.runAjax( newData );
			} else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
				control.setState( { progress: 'widgets' } );
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'kadence_after_import_data' );
				newData.append( 'security', kadenceStarterParams.ajax_nonce );
				control.runAjax( newData );
			} else if ( 'undefined' !== typeof response.status && 'pluginSuccess' === response.status ) {
				control.setState( { progress: 'content' } );
				var newData = new FormData();
				newData.append( 'action', 'kadence_import_demo_data' );
				newData.append( 'security', kadenceStarterParams.ajax_nonce );
				newData.append( 'selected', control.state.activeTemplate );
				newData.append( 'palette', control.state.colorPalette );
				control.runAjax( newData );
			} else if ( 'undefined' !== typeof response.message ) {
				jQuery( '.kadence_starter_templates_finished' ).append( '<p>' + response.message + '</p>' );
				control.setState( { finished: true, isFetching: false, activeTemplate: '', focusMode: false, isImporting: false, progress: '' } );
			} else {
				jQuery( '.kadence_starter_templates_error' ).append( '<div class="notice kadence_starter_templates_response notice-error"><p>' + response + '</p></div>' );
				control.setState( { finished: true, isFetching: false, activeTemplate: '', focusMode: false, isImporting: false, progress: '' } );
			}
		})
		.fail( function( error ) {
			jQuery( '.kadence_starter_templates_error' ).append( '<div class="notice kadence_starter_templates_response notice-error"><p>Error: ' + error.statusText + ' (' + error.status + ')' + '</p></div>' );
			control.setState( { finished: true, isFetching: false, activeTemplate: '', focusMode: false, isImporting: false, progress: '' } );
		});
	}
	render() {
		const cats = [ 'all' ];
		for ( let i = 0; i < this.state.templates.length; i++ ) {
			for ( let c = 0; c < this.state.templates[ i ].categories.length; c++ ) {
				if ( ! cats.includes( this.state.templates[ i ].categories[ c ] ) ) {
					cats.push( this.state.templates[ i ].categories[ c ] );
				}
			}
		}
		const catOptions = cats.map( ( item ) => {
			return { value: item, label: this.capitalizeFirstLetter( item ) }
		} );
		const KadenceFocusMode = () => {
			const itemArray = this.state.templates.filter( ( { key } ) => key === this.state.activeTemplate );
			const item = itemArray[0];
			let pluginsActive = true;
			let pluginsPremium = false;
			const url = ( this.state.colorPalette ? item.url + '?previewcolor=' + this.state.colorPalette : item.url );
			return (
				<div className="kadence-starter-templates-preview theme-install-overlay wp-full-overlay expanded" style={{ display:'block'}}>
					<div className="wp-full-overlay-sidebar">
						<div className="wp-full-overlay-header">
							<button
								className="kst-close-focus-btn close-full-overlay"
								onClick={ () => this.setState( { activeTemplate: '', colorPalette: '', focusMode: false } ) }
							>
							</button>
						</div>
						<div className="wp-full-overlay-sidebar-content">
							<div className="install-theme-info">
								<h3 className="theme-name">{ item.name }</h3>
								<div className="theme-by">{ item.categories.map( category => this.capitalizeFirstLetter( category ) ).join( ', ' ) }</div>
								<img className="theme-screenshot" src={ item.image } alt={ item.name } />
								<div className="palette-title-wrap">
									<h2 className="palette-title">{__( 'Optional: Choose Color Scheme', 'kadence-starter-templates' ) }</h2>
									<Button
										label={ __( 'clear' ) }
										className="kst-clear-palette"
										disabled={ this.state.colorPalette ? false : true }
										icon="image-rotate"
										iconSize={ 10 }
										onClick={ () => this.setState( { colorPalette: '' } ) }
									/>
								</div>
								<ButtonGroup className="kst-palette-group" aria-label={ __( 'Select a Palette', 'kadence-starter-templates' ) }>
									{ map( this.state.palettes, ( { palette, colors } ) => {
										return (
											<Button
												className="kst-palette-btn"
												isPrimary={ palette === this.state.colorPalette }
												aria-pressed={ palette === this.state.colorPalette }
												onClick={ () => this.setState( { colorPalette: palette } ) }
											>
												{ map( colors, ( color, index ) => {
													return (
														<div key={ index } style={ {
															width: 30,
															height: 30,
															marginBottom: 0,
															marginRight:'3px',
															transform: 'scale(1)',
															transition: '100ms transform ease',
														} } className="kadence-swatche-item-wrap">
															<span
																className={ 'kadence-swatch-item' }
																style={ {
																	height: '100%',
																	display: 'block',
																	width: '100%',
																	border: '1px solid rgb(218, 218, 218)',
																	borderRadius: '50%',
																	color: `${ color }`,
																	boxShadow: `inset 0 0 0 ${ 30 / 2 }px`,
																	transition: '100ms box-shadow ease',
																} }
																>
															</span>
														</div>
													)
												} ) }
											</Button>
										)
									} ) }
								</ButtonGroup>
								<p className="desc-small">{__( '*You can change this after import.', 'kadence-starter-templates' ) }</p>
							</div>
							<div className="kadence-starter-required-plugins">
								<h3>{ __( 'Required Plugins', 'kadence-starter-templates' ) }</h3>
								<ul className="kadence-required-wrap">
									{ map( item.plugins, ( { state, title, src } ) => {
										if ( 'active' !== state ) {
											pluginsActive = false;
											if ( 'thirdparty' === src ) {
												pluginsPremium = true;
											}
										}
										return (
											<li className="plugin-required">
												{ title } - <span class="plugin-status">{ ( 'notactive' === state ? __( 'Not Installed', 'kadence-starter-templates' ) : state ) }</span>
											</li>
										);
									} ) }
								</ul>
								{ ! pluginsActive && (
									<Fragment>
										{ pluginsPremium && (
											<p className="desc-small">{__( '*Install Missing/Inactive Premium plugins to import.', 'kadence-starter-templates' ) }</p>
										) }
										{ !pluginsPremium && (
											<p className="desc-small">{__( '*Missing/Inactive plugins will be installed on import.', 'kadence-starter-templates' ) }</p>
										) }
									</Fragment>
								) }
							</div>
						</div>

						<div class="wp-full-overlay-footer">
							<div class="kadence-starter-templates-preview-actions">
								<button
									className="kst-import-btn button-hero button button-primary"
									isDisabled={ undefined !== item.pro && item.pro && 'true' !== kadenceStarterParams.pro }
									onClick={ () => this.setState( { isImporting: true } ) }
								>
									{ __( 'Import', 'kadence-starter-templates' ) }
								</button>
							</div>
						</div>
					</div>

					<div class="wp-full-overlay-main">
						<iframe id="kadence-starter-preview" src={ url } ></iframe>
					</div>
				</div>
			);
		}
		const KadenceImportMode = () => {
			const itemArray = this.state.templates.filter( ( { key } ) => key === this.state.activeTemplate );
			const item = itemArray[0];
			let pluginsPremium = false;
			return (
				<Fragment>
					<div className="kst-grid-single-item">
						<div className="kst-template-item">
							<div className="kst-import-btn">
								<img src={ item.image } alt={ item.name } />
								<div className="demo-title">
									<h4>{ item.name }</h4>
								</div>
							</div>
						</div>
					</div>
					<Modal
						className="kst-import-modal"
						title={ __( 'Import Starter Template' ) }
						onRequestClose={ () => this.state.isFetching ? false : this.setState( { activeTemplate: '', colorPalette: '', focusMode: false, isImporting: false, progress: '' } ) }>
							{ kadenceStarterParams.has_content && (
								<div className="kadence_starter_templates_notice">
									{ kadenceStarterParams.notice }
								</div>
							) }
							<h3>{ __( 'Starter Template Plugins', 'kadence-starter-templates' ) }</h3>
							{ map( item.plugins, ( { state, title, src } ) => {
								if ( 'active' !== state ) {
									if ( 'thirdparty' === src ) {
										pluginsPremium = true;
									}
								}
							} ) }
							{ pluginsPremium && (
								<p className="desc-small install-third-party-notice">{ __( '*This starter template requires premium third-party plugins. Please install missing/inactive premium plugins to import.', 'kadence-starter-templates' ) }</p>
							) }
							<ul className="kadence-required-wrap">
								{ map( item.plugins, ( { state, title, src } ) => {
									return (
										<li className="plugin-required">
											{ title } { ( 'active' !== state && 'thirdparty' === src ? <span class="plugin-install-required">{ __( 'Please install and activate this third-party premium Plugin' ) }</span> : '' ) }
										</li>
									);
								} ) }
							</ul>
							{ this.state.colorPalette && (
								<Fragment>
									<h3>{ __( 'Selected Color Palette', 'kadence-starter-templates' ) }</h3>
									{ map( this.state.palettes, ( { palette, colors } ) => {
										if ( palette !== this.state.colorPalette ) {
											return;
										}
										return (
											<div className="kst-palette-btn kst-selected-color-palette">
												{ map( colors, ( color, index ) => {
													return (
														<div key={ index } style={ {
															width: 22,
															height: 22,
															marginBottom: 0,
															marginRight:'3px',
															transform: 'scale(1)',
															transition: '100ms transform ease',
														} } className="kadence-swatche-item-wrap">
															<span
																className={ 'kadence-swatch-item' }
																style={ {
																	height: '100%',
																	display: 'block',
																	width: '100%',
																	border: '1px solid rgb(218, 218, 218)',
																	borderRadius: '50%',
																	color: `${ color }`,
																	boxShadow: `inset 0 0 0 ${ 30 / 2 }px`,
																	transition: '100ms box-shadow ease',
																} }
																>
															</span>
														</div>
													)
												} ) }
											</div>
										)
									} ) }
								</Fragment>
							) }
							{ this.state.progress === 'plugins' && (
								<div class="kadence_starter_templates_response">{ kadenceStarterParams.plugin_progress }</div>
							) }
							{ this.state.progress === 'content' && (
								<div class="kadence_starter_templates_response">{ kadenceStarterParams.content_progress }</div>
							) }
							{ this.state.progress === 'contentNew' && (
								<div class="kadence_starter_templates_response">{ kadenceStarterParams.content_new_progress }</div>
							) }
							{ this.state.progress === 'customizer' && (
								<div class="kadence_starter_templates_response">{ kadenceStarterParams.customizer_progress }</div>
							) }
							{ this.state.progress === 'widgets' && (
								<div class="kadence_starter_templates_response">{ kadenceStarterParams.widgets_progress }</div>
							) }
							{ this.state.isFetching && (
								<Spinner />
							) }
							{ ! kadenceStarterParams.isKadence && (
								<div class="kadence_starter_templates_response">
									<h2>{ __( 'This Starter Template Requires the Kadence Theme', 'kadence-starter-templates' ) }</h2>
									<ExternalLink href={ 'https://kadence-theme.com/' }>{ __( 'Get Free Theme', 'kadence-blocks' ) }</ExternalLink>
								</div>
							) }
							{ kadenceStarterParams.isKadence && (
								<Fragment>
									{ pluginsPremium && (
										<Button className="kt-defaults-save" isPrimary disabled={ this.state.isFetching } onClick={ () => {
											this.runPluginInstall( item.key );
										} }>
											{ __( 'Skip and Import with Partial Content' ) }
										</Button>
									) }
									{ ! pluginsPremium && (
										<Button className="kt-defaults-save" isPrimary disabled={ this.state.isFetching } onClick={ () => {
											this.runPluginInstall( item.key );
										} }>
											{ __( 'Start Importing' ) }
										</Button>
									) }
								</Fragment>
							) }
					</Modal>
				</Fragment>
			);
		}
		const KadenceSitesGrid = () => (
			<div className="templates-grid">
				{ map( this.state.templates, ( { name, key, image, content, categories, keywords, pro } ) => {
					if ( ( 'all' === this.state.category || categories.includes( this.state.category ) ) && ( ! this.state.search || ( keywords && keywords.some( x => x.toLowerCase().includes( this.state.search.toLowerCase() ) ) ) ) ) {
						return (
							<div className="kst-template-item">
								<Button
									key={ key }
									className="kst-import-btn"
									isSmall
									isDisabled={ undefined !== pro && pro && 'true' !== kadenceStarterParams.pro }
									onClick={ () => this.focusMode( key ) }
								>
									<LazyLoad>
										<img src={ image } alt={ name } />
									</LazyLoad>
									<div className="demo-title">
										<h4>{ name }</h4>
									</div>
								</Button>
								{ undefined !== pro && pro && (
									<Fragment>
										<span className="kb-pro-template">{ __( 'Pro', 'kadence-blocks' ) }</span>
										{ 'true' !== kadenceStarterParams.pro && (
											<div className="kt-popover-pro-notice">
												<h2>{ __( 'Kadence Pro required for this item', 'kadence-starter-sites' ) } </h2>
												<ExternalLink href={ 'https://www.kadencewp.com/pro/' }>{ __( 'Upgrade to Pro', 'kadence-blocks' ) }</ExternalLink>
											</div>
										) }
									</Fragment>
								) }
							</div>
						);
					}
				} ) }
			</div>
		);

		const MainPanel = () => (
			<div className="main-panel">
				{ this.state.focusMode && (
					<Fragment>
						{ this.state.isImporting && (
							<KadenceImportMode />
						) }
						{ ! this.state.isImporting && (
							<KadenceFocusMode />
						) }
					</Fragment>
				) }
				{ ! this.state.focusMode && ! this.state.finished && (
					<KadenceSitesGrid />
				) }
			</div>
		);

		return (
			<Fragment>
				<MainPanel />
			</Fragment>
		);
	}
}

wp.domReady( () => {
	render(
		<KadenceImporter />,
		document.querySelector( '.kadence_starter_dashboard_main' )
	);
} );
