<?php

if ( !defined( 'MEDIAWIKI' ) ) die();

/**
 * @file
 * @ingroup Extensions
 *
 * @author Yaron Koren
 */

define( 'APPROVED_REVS_VERSION', '1.0.0' );

// credits
$wgExtensionCredits['other'][] = array(
	'path'            => __FILE__,
	'name'            => 'Approved Revs',
	'version'         => APPROVED_REVS_VERSION,
	'author'          => array( 'Yaron Koren', '...' ),
	'url'             => 'https://www.mediawiki.org/wiki/Extension:Approved_Revs',
	'descriptionmsg'  => 'approvedrevs-desc'
);

// global variables
$egApprovedRevsIP = __DIR__ . '/';
$egApprovedRevsNamespaces = array( NS_MAIN, NS_USER, NS_PROJECT, NS_TEMPLATE, NS_HELP );
$egApprovedRevsSelfOwnedNamespaces = array();
$egApprovedRevsBlankIfUnapproved = false;
$egApprovedRevsAutomaticApprovals = true;
$egApprovedRevsShowApproveLatest = false;
$egApprovedRevsShowNotApprovedMessage = false;

// internationalization
$wgMessagesDirs['ApprovedRevs'] = $egApprovedRevsIP . 'i18n';
$wgExtensionMessagesFiles['ApprovedRevs'] = $egApprovedRevsIP . 'languages/ApprovedRevs.i18n.php';
$wgExtensionMessagesFiles['ApprovedRevsAlias'] = $egApprovedRevsIP . 'languages/ApprovedRevs.alias.php';
$wgExtensionMessagesFiles['ApprovedRevsMagic'] = $egApprovedRevsIP . 'languages/ApprovedRevs.i18n.magic.php';

// autoload classes
$wgAutoloadClasses['ApprovedRevs'] = $egApprovedRevsIP . 'includes/ApprovedRevs_body.php';
$wgAutoloadClasses['ApprovedRevsHooks'] = $egApprovedRevsIP . 'includes/ApprovedRevsHooks.php';
$wgAutoloadClasses['SpecialApprovedRevs'] = $egApprovedRevsIP . 'includes/specials/SpecialApprovedRevs.php';
$wgAutoloadClasses['SpecialApprovedFiles'] = $egApprovedRevsIP . 'includes/specials/SpecialApprovedFiles.php';
$wgAutoloadClasses['SpecialApprovedRevsQueryPage'] = $egApprovedRevsIP . 'includes/specials/SpecialApprovedRevsQueryPage.php';
$wgAutoloadClasses['SpecialApprovedFilesQueryPage'] = $egApprovedRevsIP . 'includes/specials/SpecialApprovedFilesQueryPage.php';

// special pages
$wgSpecialPages['ApprovedRevs'] = 'SpecialApprovedRevs';
$wgSpecialPages['ApprovedFiles'] = 'SpecialApprovedFiles';
$wgSpecialPageGroups['ApprovedRevs'] = 'pages';
$wgSpecialPageGroups['ApprovedFiles'] = 'pages';

// hooks
$wgHooks['ArticleEditUpdates'][] = 'ApprovedRevsHooks::updateLinksAfterEdit';
$wgHooks['ArticleSaveComplete'][] = 'ApprovedRevsHooks::setLatestAsApproved';
$wgHooks['ArticleSaveComplete'][] = 'ApprovedRevsHooks::setSearchText';
$wgHooks['SearchResultInitFromTitle'][] = 'ApprovedRevsHooks::setSearchRevisionID';
$wgHooks['PersonalUrls'][] = 'ApprovedRevsHooks::removeRobotsTag';
$wgHooks['ArticleFromTitle'][] = 'ApprovedRevsHooks::showApprovedRevision';
$wgHooks['ArticleAfterFetchContent'][] = 'ApprovedRevsHooks::showBlankIfUnapproved';
// MW 1.21+
$wgHooks['ArticleAfterFetchContentObject'][] = 'ApprovedRevsHooks::showBlankIfUnapproved2';
$wgHooks['DisplayOldSubtitle'][] = 'ApprovedRevsHooks::setSubtitle';
// it's 'SkinTemplateNavigation' for the Vector skin, 'SkinTemplateTabs' for
// most other skins
$wgHooks['SkinTemplateTabs'][] = 'ApprovedRevsHooks::changeEditLink';
$wgHooks['SkinTemplateNavigation'][] = 'ApprovedRevsHooks::changeEditLinkVector';
$wgHooks['PageHistoryBeforeList'][] = 'ApprovedRevsHooks::storeApprovedRevisionForHistoryPage';
$wgHooks['PageHistoryLineEnding'][] = 'ApprovedRevsHooks::addApprovalLink';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::setAsApproved';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::unsetAsApproved';
$wgHooks['BeforeParserFetchTemplateAndtitle'][] = 'ApprovedRevsHooks::setTranscludedPageRev';
$wgHooks['ArticleDeleteComplete'][] = 'ApprovedRevsHooks::deleteRevisionApproval';
$wgHooks['MagicWordwgVariableIDs'][] = 'ApprovedRevsHooks::addMagicWordVariableIDs';
$wgHooks['ParserBeforeTidy'][] = 'ApprovedRevsHooks::handleMagicWords';
$wgHooks['AdminLinks'][] = 'ApprovedRevsHooks::addToAdminLinks';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'ApprovedRevsHooks::describeDBSchema';
$wgHooks['EditPage::showEditForm:initial'][] = 'ApprovedRevsHooks::addWarningToEditPage';
$wgHooks['sfHTMLBeforeForm'][] = 'ApprovedRevsHooks::addWarningToSFForm';
$wgHooks['ArticleViewHeader'][] = 'ApprovedRevsHooks::setArticleHeader';
$wgHooks['ArticleViewHeader'][] = 'ApprovedRevsHooks::displayNotApprovedHeader';

// Approved File Revisions
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::setFileAsApproved';
$wgHooks['UnknownAction'][] = 'ApprovedRevsHooks::unsetFileAsApproved';
$wgHooks['ImagePageFileHistoryLine'][] = 'ApprovedRevsHooks::onImagePageFileHistoryLine';
$wgHooks['BeforeParserFetchFileAndTitle'][] = 'ApprovedRevsHooks::ModifyFileLinks';
$wgHooks['ImagePageFindFile'][] = 'ApprovedRevsHooks::onImagePageFindFile';
$wgHooks['FileDeleteComplete'][] = 'ApprovedRevsHooks::onFileDeleteComplete';


// logging
$wgLogTypes['approval'] = 'approval';
$wgLogNames['approval'] = 'approvedrevs-logname';
$wgLogHeaders['approval'] = 'approvedrevs-logdesc';
$wgLogActions['approval/approve'] = 'approvedrevs-approveaction';
$wgLogActions['approval/unapprove'] = 'approvedrevs-unapproveaction';

// user rights
$wgAvailableRights[] = 'approverevisions'; // jamesmontalvo3: do we remove this or leave it behind even though it's not being used anymore?
$wgGroupPermissions['sysop']['approverevisions'] = true; // jamesmontalvo3: do we remove this or leave it behind even though it's not being used anymore?
$wgAvailableRights[] = 'viewlinktolatest';
$wgGroupPermissions['*']['viewlinktolatest'] = true;

// page properties
$wgPageProps['approvedrevs'] = 'Whether or not the page is approvable';

// ResourceLoader modules
$wgResourceModules['ext.ApprovedRevs'] = array(
	'styles' => 'ApprovedRevs.css',
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'ApprovedRevs'
);
