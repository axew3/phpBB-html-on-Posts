<?php
/**
 *
 * w3 WP common. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace w3all\w3wpcommon\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * w3 WP common Event listener.
 */
class main_listener implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
      'core.viewtopic_modify_post_data' => 'viewtopic_modify_post_data',
      //'core.modify_username_string' => 'modify_username_string',
      'core.user_setup' => 'load_language_on_setup',
      //'core.display_forums_modify_template_vars'  => 'display_forums_modify_template_vars',
    ];
  }

  protected $db;
  protected $language;

  public function __construct(\phpbb\db\driver\factory $db,\phpbb\language\language $language)
  {
    $this->db = $db;
    $this->language = $language; 
  }




  /**
   * Load common language files during user setup
   *
   * @param \phpbb\event\data $event  Event object
   */
  public function load_language_on_setup($e)
  {
    /*$this->gid = $e['user_data']['group_id'];
    $this->uid = $e['user_data']['user_id'];
    $this->user_type = $e['user_data']['user_type'];*/

    $lang_set_ext = $e['lang_set_ext'];
    $lang_set_ext[] = [
      'ext_name' => 'w3all/w3wpcommon',
      'lang_set' => 'common',
    ];
    $e['lang_set_ext'] = $lang_set_ext;
  }

  /**
   * A sample PHP event
   * Modifies the names of the forums on index
   *
   * @param \phpbb\event\data $event  Event object
   */
  public function modify_username_string($e)
  {
    /*echo'<pre>';
   print_r($e);
    die();*/

    //$e['username'] =  'Marco Aurelio';
    //$e['username_string'] = '<a href>Marco Aurelio</a>';
    //$e['username_string'] = '<a href="./memberlist.php?mode=viewprofile&u='.$e['user_id'].'">MArco Aurelio</a>';

     //print_r($e);
    //die();
  }


  public function viewtopic_modify_post_data($e)
  {
    $e_rowset = $e['rowset'];

      foreach($e_rowset as $p => $pp){ 
      	// only if the case we execute this
     if ( substr($pp['post_text'], 0, 15) == '<t>[HTMLMARKUP]' )  // ** can be changed to a custom word
     {
       $sql = "SELECT group_id FROM " . USERS_TABLE . " WHERE user_id = ".$pp['user_id']."";
        $res = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($res);
        $this->db->sql_freeresult($res);
      
       if( $row['group_id'] == 5 )
       { 
       	 $parse_flags = ($pp['bbcode_bitfield'] ? OPTION_FLAG_BBCODE : 0) | OPTION_FLAG_SMILIES;
       	 // ** the HTMLMARKUP placeholder can be changed into something custom, so that only users that know the magic word can add HTML CODE that will be parsed,
       	 // this just avoid that nobody can joke with the thing slowing down the foreach, adding the magic word, even if for him it will never be parsed...
         $e_rowset[$p]['post_text'] = str_replace("[HTMLMARKUP]", "", $pp['post_text'], $count); // $count not used, but could for more complex things
          // !! if the post_text will contain also one single bbcode, the following line will NOT let parse as html the subsequent html_entity_decode()
          // $e_rowset[$p]['post_text'] = generate_text_for_display($e_rowset[$p]['post_text'], $pp['bbcode_uid'], $pp['bbcode_bitfield'], $parse_flags, true);
         $e_rowset[$p]['post_text'] = html_entity_decode($e_rowset[$p]['post_text']);
        }
      }
     }
     
   $e['rowset'] = $e_rowset;
   unset($e_rowset);
  }

    public function viewtopic_post_rowset_data($e)
  {

  }

}
