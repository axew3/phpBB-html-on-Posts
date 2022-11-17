<?php
/**
 *
 * HTML on posts. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace w3all\htmlposts\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
      'core.viewtopic_modify_post_data' => 'viewtopic_modify_post_data',
    ];
  }

  protected $db;

  public function __construct(\phpbb\db\driver\factory $db)
  {
    $this->db = $db;
  }

  public function viewtopic_modify_post_data($e)
  {
    $e_rowset = $e['rowset'];

      foreach($e_rowset as $p => $pp){
        // only if the case we execute this 
        // normally will be '<t>[HTMLMARKUP]'
     if ( substr($pp['post_text'], 3, 12) == '[HTMLMARKUP]' )  // ** can be changed to a custom word
     { // only the default phpBB user's groupID retrieved here
       $sql = "SELECT group_id FROM " . USERS_TABLE . " WHERE user_id = ".$pp['user_id']."";
        $res = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($res);
        $this->db->sql_freeresult($res);

       if( $row['group_id'] == 5 ) // only admins can add posts that after can be parsed. Add more groupsIDS here if you want
       {
         // ** the [HTMLMARKUP] placeholder can be changed into something custom 
         // this just avoid that nobody but who know it, can add the magic word, into a post that by the way, will never be parsed as HTML if the user do not belong to specified group
         
         // NOTE: if we want to display/render (not parse) some active bbcode like [b], then entities should be used 
         // &#91;b&#93; test me i want to show bbcode tags &#91;/b&#93;  <- will return ->  [b] test me i want to show bbcode tags [/b]
         // NOTE: if a bbcode is found into the post text, then the post will not be parsed as HTML due to generate_text_for_display() that fire after on viewtopic.php
         $e_rowset[$p]['post_text'] = str_replace("[HTMLMARKUP]", "", $pp['post_text'], $count); // $count not used, but could for more complex things
         $e_rowset[$p]['post_text'] = html_entity_decode(trim($e_rowset[$p]['post_text']));
         // generate_text_for_display() will be by the way executed after into viewtopic.php
         // !! if the post_text will contain also one single bbcode, then the post will be re-parsed as phpBB text

        }
      }
     }

   $e['rowset'] = $e_rowset;
   unset($e_rowset);
  }

}
