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
  {    //return;

    $e_rowset = $e['rowset'];
     //unset($e['rowset']);
     echo'<pre>';
     unset($e_rowset['rowset'][5394]['post_text']);
     //echo $e['rowset'][5394]['post_text'] = '00000000000';
 // print_r( $e  );exit;
  //   print_r( $e );exit;
        foreach($e_rowset  as  $p =>  $pp  ){
          //if($p['post_text']){
            //echo $p['post_text'];

// echo str_replace("[HTMLMARKUP]", "", $p['post_text'], $count);
//echo $count;
        //  echo substr($p['post_text'], 0, 15);  exit;
     if ( substr($pp['post_text'], 0, 15) == '<t>[HTMLMARKUP]' )
     {

                $sql = "SELECT group_id
                FROM " . USERS_TABLE . "
                WHERE user_id = ".$pp['user_id']."";
                 $res = $this->db->sql_query($sql);
                 $row = $this->db->sql_fetchrow($res);
                 $this->db->sql_freeresult($res);
               //print_r($row['group_id']);//exit;

              if( $row['group_id'] == 5 )
              {
                //$pp['post_text'] = str_replace("[HTMLMARKUP]", "", $pp['post_text'], $count); // $count not used but could
              // unset($e['rowset'][$p]['post_text']);
                $e_rowset[$p]['post_text'] = str_replace("[HTMLMARKUP]", "", $pp['post_text'], $count); // $count not used but could
              }

            // echo $p['post_text'] .'<br />';//exit;
              //$temp_Erowset[$ee]['post_text'] = html_entity_decode($temp_Erowset[$ee]['post_text']);
            //}
        }

    // print_r($e_rowset );
       //if( $temp_Erowset[$ee]){
      //print_r($temp_Erowset[$ee]['post_text']);exit;

        //$parse_flags = ($tempRW[$fpid]['bbcode_bitfield'] ? OPTION_FLAG_BBCODE : 0) | OPTION_FLAG_SMILIES;
        //$tempRW[$fpid]['post_text'] = html_entity_decode($dbd['rep_content']) . generate_text_for_display($tempRW[$fpid]['post_text'], $tempRW[$fpid]['bbcode_uid'], $tempRW[$fpid]['bbcode_bitfield'], $parse_flags, true);


      // }
     }
     echo'<pre>';
  //    $e['rowset'] = $e_rowset;
  //    unset($e_rowset);
      print_r($e_rowset);
     exit;


    echo'<pre>';

    print_r($e );exit;
    $postlist = $e['post_list'];
    $temp_Erowset = $e['rowset'];
     echo $e['user_cache'];
    unset($e['rowset']);


    #print_r($e['rowset']); //exit;
    //html_entity_decode($dbd['rep_content']) .
    foreach($postlist as $ee  ){
     //print_r($temp_Erowset[$ee]);
       //if( $temp_Erowset[$ee]){
      //print_r($temp_Erowset[$ee]['post_text']);exit;

        //$parse_flags = ($tempRW[$fpid]['bbcode_bitfield'] ? OPTION_FLAG_BBCODE : 0) | OPTION_FLAG_SMILIES;
        //$tempRW[$fpid]['post_text'] = html_entity_decode($dbd['rep_content']) . generate_text_for_display($tempRW[$fpid]['post_text'], $tempRW[$fpid]['bbcode_uid'], $tempRW[$fpid]['bbcode_bitfield'], $parse_flags, true);

      $temp_Erowset[$ee]['post_text'] = html_entity_decode($temp_Erowset[$ee]['post_text']);
      // }
     }
  #print_r($temp_Erowset);

  $e['rowset'] = $temp_Erowset;
  unset($temp_Erowset);
 #echo'<pre>';
    #print_r($e['rowset']);
    #die();
  }

    public function viewtopic_post_rowset_data($e)
  {

  }

}
