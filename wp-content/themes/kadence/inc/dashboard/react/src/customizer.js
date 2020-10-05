/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
import map from 'lodash/map';
const { withFilters, TabPanel, Panel, PanelBody, PanelRow, Button } = wp.components;

export const CustomizerLinks = () => {
	const headerLinks = [
		{
			title: __( 'Header Layout', 'kadence' ),
			description: __( 'Add elements and arrage them how you want.', 'kadence' ),
			focus: 'kadence_customizer_header',
			type: 'panel',
			setting: false
		},
		{
			title: __( 'Branding', 'kadence' ),
			description: __( 'Upload your logo and favicon.', 'kadence' ),
			focus: 'title_tagline',
			type: 'section',
			setting: false
		},
		{
			title: __( 'Transparent Header', 'kadence' ),
			description: __( 'Set the header to be transparent and blend with the page title.', 'kadence' ),
			focus: 'kadence_customizer_transparent_header',
			type: 'section',
			setting: false
		},
		{
			title: __( 'Sticky Header', 'kadence' ),
			description: __( 'Set header to stick to top of screen while scrolling.', 'kadence' ),
			focus: 'kadence_customizer_sticky_header',
			type: 'section',
			setting: false
		},
	];
	return (
		<Fragment>
			<h2 className="section-header">{ __( 'Customize Your Site', 'kadence' ) }</h2>
			{/* <h3 className="section-sub-head">{ __( 'Header Builder', 'kadence' ) }</h3> */}
			<div className="two-col-grid">
				{ map( headerLinks, ( link ) => {
					return (
						<div className="link-item">
							<h4>{ link.title }</h4>
							<p>{ link.description }</p>
							<div className="link-item-foot">
								<a href={ `${kadenceDashboardParams.adminURL}customize.php?autofocus%5B${ link.type }%5D=${ link.focus }` }>
									{ __( 'Customize', 'kadence') }
								</a>
							</div>
						</div>
					);
				} ) }
			</div>
		</Fragment>
	);
};

export default withFilters( 'kadence_theme_customizer' )( CustomizerLinks );