/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { withFilters } = wp.components;
import ChangelogItem from './changelog-item';

export const ChangelogTab = () => {
	return (
		<Fragment>
			{ kadenceDashboardParams.changelog && (
				<Fragment>
					{ kadenceDashboardParams.changelog.map( ( item, index ) => {
						return <ChangelogItem
							item={ item }
							index={ item }
						/>;
					} ) }
				</Fragment>
			) }
		</Fragment>
	);
};

export default withFilters( 'kadence_theme_changelog' )( ChangelogTab );