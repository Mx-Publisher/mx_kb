<?php
/**
*
* @package MX-Publisher Module - mx_kb
* @version $Id: functions_auth.php,v 1.8 2008/07/13 19:34:16 jonohlsson Exp $
* @copyright (c) 2002-2006 [wGEric, Jon Ohlsson] MX-Publisher Project Team
* @license http://opensource.org/licenses/gpl-license.php GNU General Public License v2
*
*/

if ( !defined( 'IN_PORTAL' ) )
{
	die( "Hacking attempt" );
}

/**
 * Auth Defs.
 *
 */
define('KB_AUTH_ALL', 0); // In reality only option used ;)
define('KB_AUTH_VIEW', 1);
define('KB_AUTH_EDIT', 2);
define('KB_AUTH_DELETE', 3);
define('KB_AUTH_POST', 4);
define('KB_AUTH_RATE', 5);

define('KB_AUTH_VIEW_COMMENT', 10);
define('KB_AUTH_POST_COMMENT', 11);
define('KB_AUTH_EDIT_COMMENT', 12);
define('KB_AUTH_DELETE_COMMENT', 13);

define('KB_AUTH_APPROVAL', 20);
define('KB_AUTH_APPROVAL_EDIT', 21);

/**
 * Auth API.
 *
 * $class->auth_user['auth_view'];
 * $class->auth_user['auth_post'];
 * $class->auth_user['auth_edit'];
 * $class->auth_user['auth_delete'];
 *
 * $class->auth_user['auth_approval'];
 * $class->auth_user['auth_approval_edit'];
 *
 * $class->auth_user['auth_rate'];
 *
 * $class->auth_user['auth_view_comment'];
 * $class->auth_user['auth_post_comment'];
 * $class->auth_user['auth_edit_comment'];
 * $class->auth_user['auth_delete_comment'];
 *
 * $class->auth_user['auth_mod'];
 */

/**
 * Enter description here...
 *
 */
class mx_kb_auth
{
	/** @var \orynider\pafiledb\core\functions */
	protected $functions;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user */
	protected $user;	
	/** @var \phpbb\db\driver\driver_interface */
	protected $db; 
	/** @var \phpbb\request\request */
	protected $request; 	
	/** @var \phpbb\auth\auth */
	protected $auth;
	
	var $auth_user = array();
	var $auth_global = array();
	
	public function mx_pafiledb_auth()
	{
		global $mx_kb_functions, 
		$template, 
		$mx_user, 
		$db,
		$mx_request_vars,
		$phpbb_auth;
		
		$this->functions 	= $mx_kb_functions;
		$this->template 	= $template;
		$this->user 		= $mx_user;
		$this->db 			= $db;
		$this->request 	= $mx_request_vars;
		$this->auth 		= $phpbb_auth;
		
		$this->is_admin = ( $this->user->data['user_level'] == ADMIN && $this->user->data['session_logged_in'] ) ? true : 0;
		$this->is_mod = ( $this->user->data['user_level'] == MOD && $this->user->data['session_logged_in'] ) ? true : 0;				

		$this->auth_fields = array( 'auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment', 'auth_approval', 'auth_approval_edit' );
		$this->auth_fields_global = array( 'auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall' );
		
		// Read out config values
		$this->kb_config = $mx_kb_functions->config_values();
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $type
	 * @param unknown_type $cat_id
	 * @param unknown_type $userdata
	 * @param unknown_type $f_access
	 * @return unknown
	 */
	function auth( $type, $cat_id, $userdata, $f_access = '' )
	{
		global $db, $lang;

		switch ( $type )
		{
			case KB_AUTH_ALL:
				$a_sql = 'a.auth_view, a.auth_edit, a.auth_delete, a.auth_post, a.auth_rate, a.auth_view_comment, a.auth_post_comment, a.auth_edit_comment, a.auth_delete_comment, a.auth_approval, a.auth_approval_edit';
				$a_sql_groups = 'a.auth_view_groups, a.auth_edit_groups, a.auth_delete_groups, a.auth_post_groups, a.auth_rate_groups, a.auth_view_comment_groups, a.auth_post_comment_groups, a.auth_edit_comment_groups, a.auth_delete_comment_groups, a.auth_approval_groups, a.auth_approval_edit_groups';
				$auth_fields = array( 'auth_view', 'auth_edit', 'auth_delete', 'auth_post', 'auth_rate', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment', 'auth_approval', 'auth_approval_edit' );
				$auth_fields_groups = array( 'auth_view_groups', 'auth_edit_groups', 'auth_delete_groups', 'auth_post_groups', 'auth_rate_groups', 'auth_view_comment_groups', 'auth_post_comment_groups', 'auth_edit_comment_groups', 'auth_delete_comment_groups', 'auth_approval_groups', 'auth_approval_edit_groups' );
			break;

			case KB_AUTH_VIEW:
				$a_sql = 'a.auth_view';
				$a_sql_groups = 'a.auth_view_groups';
				$auth_fields = array( 'auth_view' );
				$auth_fields_groups = array( 'auth_view_groups' );
			break;

			case KB_AUTH_EDIT:
				$a_sql = 'a.auth_edit';
				$a_sql_groups = 'a.auth_edit_groups';
				$auth_fields = array( 'auth_edit' );
				$auth_fields_groups = array( 'auth_edit_groups' );
			break;

			case KB_AUTH_DELETE:
				$a_sql = 'a.auth_delete';
				$a_sql_groups = 'a.auth_delete_groups';
				$auth_fields = array( 'auth_delete' );
				$auth_fields_groups = array( 'auth_delete_groups' );
			break;

			case KB_AUTH_POST:
				$a_sql = 'a.auth_post';
				$a_sql_groups = 'a.auth_post_groups';
				$auth_fields = array( 'auth_post' );
				$auth_fields_groups = array( 'auth_post_groups' );
			break;

			case KB_AUTH_RATE:
				$a_sql = 'a.auth_rate';
				$a_sql_groups = 'a.auth_rate_groups';
				$auth_fields = array( 'auth_rate' );
				$auth_fields_groups = array( 'auth_rate_groups' );
			break;

			case KB_AUTH_VIEW_COMMENT:
				$a_sql = 'a.auth_view_comment';
				$a_sql_groups = 'a.auth_view_comment_groups';
				$auth_fields = array( 'auth_view_comment' );
				$auth_fields_groups = array( 'auth_view_comment_groups' );
				break;

			case KB_AUTH_POST_COMMENT:
				$a_sql = 'a.auth_post_comment';
				$a_sql_groups = 'a.auth_post_comment_groups';
				$auth_fields = array( 'auth_post_comment' );
				$auth_fields_groups = array( 'auth_post_comment_groups' );
			break;

			case KB_AUTH_EDIT_COMMENT:
				$a_sql = 'a.auth_edit_comment';
				$a_sql_groups = 'a.auth_edit_comment_groups';
				$auth_fields = array( 'auth_edit_comment' );
				$auth_fields_groups = array( 'auth_edit_comment_groups' );
			break;

			case KB_AUTH_DELETE_COMMENT:
				$a_sql = 'a.auth_delete_comment';
				$a_sql_groups = 'a.auth_delete_comment_groups';
				$auth_fields = array( 'auth_delete_comment' );
				$auth_fields_groups = array( 'auth_delete_comment_groups' );
			break;

			case KB_AUTH_APPROVAL:
				$a_sql = 'a.auth_approval';
				$a_sql_groups = 'a.auth_approval_groups';
				$auth_fields = array( 'auth_approval' );
				$auth_fields_groups = array( 'auth_approval_groups' );
			break;

			case KB_AUTH_APPROVAL_EDIT:
				$a_sql = 'a.auth_approval_edit';
				$a_sql_groups = 'a.auth_approval_edit_groups';
				$auth_fields = array( 'auth_approval_edit' );
				$auth_fields_groups = array( 'auth_approval_edit_groups' );
			break;

			default:
			break;
		}
		
		$is_admin = ( $userdata['user_level'] == ADMIN && $userdata['session_logged_in'] ) ? true : 0;
		
		//
		// If f_access has not been passed, or auth is needed to return an array of forums
		// then we need to pull the auth information on the given forum (or all forums)
		//
		if ( empty($f_access) )
		{
			$forum_match_sql = ( $cat_id != AUTH_LIST_ALL ) ? "WHERE a.category_id = $cat_id" : '';

			$sql = "SELECT *
				FROM " . KB_CATEGORIES_TABLE . " a
				$forum_match_sql";
			if ( !($result = $db->sql_query($sql)) )
			{
				mx_message_die(GENERAL_ERROR, 'Failed obtaining forum access control lists', '', __LINE__, __FILE__, $sql);
			}

			$sql_fetchrow = ( $cat_id != AUTH_LIST_ALL ) ? 'sql_fetchrow' : 'sql_fetchrowset';

			if ( !($f_access = $db->$sql_fetchrow($result)) )
			{
				$db->sql_freeresult($result);
				return array();
			}
			$db->sql_freeresult($result);
		}

		$auth_user = array();
		for( $i = 0; $i < count( $auth_fields ); $i++ )
		{
			$key = $auth_fields[$i];
			$key_groups = $auth_fields_groups[$i];
			// If the user is logged on and the module type is either ALL or REG then the user has access
			// If the type if ACL, MOD or ADMIN then we need to see if the user has specific permissions
			// to do whatever it is they want to do ... to do this we pull relevant information for the
			// user (and any groups they belong to)
			// Now we compare the users access level against the modules. We assume here that a moderator
			// and admin automatically have access to an ACL module, similarly we assume admins meet an
			// auth requirement of MOD

			if ( $cat_id != AUTH_LIST_ALL )
			{
				$value = $f_access[$key];
				$value_groups = $f_access[$key_groups];

				switch ( $value )
				{
					case AUTH_ALL:
						$this->auth_user[$key] = true;
						$this->auth_user[$key . '_type'] = $lang['Auth_Anonymous_users'];
					break;

					case AUTH_REG:
						$this->auth_user[$key] = ( $userdata['session_logged_in'] ) ? true : 0;
						$this->auth_user[$key . '_type'] = $lang['Auth_Registered_Users'];
					break;

					case AUTH_ANONYMOUS:
						$this->auth_user[$key] = ( ! $userdata['session_logged_in'] ) ? true : 0;
						$this->auth_user[$key . '_type'] = $lang['Auth_Anonymous_users'];
					break;

					case AUTH_ACL: // PRIVATE
						$this->auth_user[$key] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $value_groups ) || mx_is_group_member( $f_access['auth_moderator_groups'] ) || $is_admin : 0;
						$this->auth_user[$key . '_type'] = $lang['Auth_Users_granted_access'];
					break;

					case AUTH_MOD:
						$this->auth_user[$key] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $f_access['auth_moderator_groups'] ) || $is_admin : 0;
						$this->auth_user[$key . '_type'] = $lang['Auth_Moderators'];
					break;

					case AUTH_ADMIN:
						$this->auth_user[$key] = $is_admin;
						$this->auth_user[$key . '_type'] = $lang['Auth_Administrators'];
					break;

					default:
						$this->auth_user[$key] = 0;
					break;
				}

				//
				// Fix for multiblocks
				//
				$this->auth_user[$key] = $this->ns_auth_cat($cat_id) && $this->auth_user[$key];
			}
			else
			{
			 	for($k = 0; $k < count($f_access); $k++)
				{
					$value = $f_access[$k][$key];
					$value_groups = $f_access[$k][$key_groups];

					$f_cat_id = $f_access[$k]['category_id'];


					switch ( $value )
					{
						case AUTH_ALL:
							$this->auth_user[$f_cat_id][$key] = true;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Anonymous_users'];
						break;

						case AUTH_REG:
							$this->auth_user[$f_cat_id][$key] = ( $userdata['session_logged_in'] ) ? true : 0;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Registered_Users'];
						break;

						case AUTH_ANONYMOUS:
							$this->auth_user[$f_cat_id][$key] = ( ! $userdata['session_logged_in'] ) ? true : 0;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Anonymous_users'];
							break;

						case AUTH_ACL: // PRIVATE
							$this->auth_user[$f_cat_id][$key] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $value_groups ) || mx_is_group_member( $f_access[$k]['auth_moderator_groups'] ) || $is_admin : 0;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Users_granted_access'];
						break;

						case AUTH_MOD:
							$this->auth_user[$f_cat_id][$key] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $f_access[$k]['auth_moderator_groups'] ) || $is_admin : 0;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Moderators'];
						break;

						case AUTH_ADMIN:
							$this->auth_user[$f_cat_id][$key] = $is_admin;
							$this->auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Administrators'];
						break;

						default:
							$this->auth_user[$f_cat_id][$key] = 0;
						break;
					}

					//
					// Fix for multiblocks
					//
					$this->auth_user[$f_cat_id][$key] = $this->ns_auth_cat($f_cat_id) && $this->auth_user[$f_cat_id][$key];

				}
			}
		}

		//
		// Is user a moderator?
		//
		if ( $cat_id != AUTH_LIST_ALL )
		{
			$this->auth_user['auth_mod'] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $f_access['auth_moderator_groups'] ) || $is_admin : 0;

			//
			// Fix for multiblocks
			//
			$this->auth_user['auth_mod'] = $this->ns_auth_cat($cat_id) && $this->auth_user['auth_mod'];
		}
		else
		{
			for($k = 0; $k < count($f_access); $k++)
			{
				$f_cat_id = $f_access[$k]['category_id'];

				$this->auth_user[$f_cat_id]['auth_mod'] = ( $userdata['session_logged_in'] ) ? mx_is_group_member( $f_access[$k]['auth_moderator_groups'] ) || $is_admin : 0;

				//
				// Fix for multiblocks
				//
				$this->auth_user[$f_cat_id]['auth_mod'] = $this->ns_auth_cat($f_cat_id) && $this->auth_user[$f_cat_id]['auth_mod'];
			}
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cat_id
	 * @return unknown
	 */
	function ns_auth_cat( $cat_id )
	{
		global $kb_type_select_data, $kb_config;

		if ( !MXBB_MODULE || MXBB_27x || !isset($kb_type_select_data) || empty($kb_type_select_data) )
		{
			return true;
		}

		return $kb_type_select_data[$cat_id] == 1;
	}

	/**
 	* get_auth_forum
 	*
 	* @param unknown_type $mode
 	* @return unknown
 	*/
	function get_auth_forum($mode = 'kb')
	{
		global $userdata, $mx_root_path, $phpEx;

		//
		// Try to reuse auth_view query result.
		//
		$userdata_key = 'mx_get_auth_' . $mode . $userdata['user_id'];
		if( !empty($userdata[$userdata_key]) )
		{
			$auth_data_sql = $userdata[$userdata_key];
			return $auth_data_sql;
		}

		//
		// Now, this tries to optimize DB access involved in auth(),
		// passing AUTH_LIST_ALL will load info for all forums at once.
		//
		$this->auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);
		$is_auth_ary = $mx_kb_auth->auth_user;

		//
		// Loop through the list of forums to retrieve the ids for
		// those with AUTH_VIEW allowed.
		//
		$auth_data_sql = '';
		foreach( $is_auth_ary as $fid => $is_auth_row )
		{
			if( ($is_auth_row['auth_view']) )
			{
				$auth_data_sql .= ( $auth_data_sql != '' ) ? ', ' . $fid : $fid;
			}
		}

		if( empty($auth_data_sql) )
		{
			$auth_data_sql = -1;
		}

		$userdata[$userdata_key] = $auth_data_sql;
		return $auth_data_sql;
	}
}
?>