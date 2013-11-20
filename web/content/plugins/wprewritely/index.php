<?php
/**
 *
 * Plugin Name: WP Rewritely
 * Plugin URI:  http://wprewritely.com
 * Description: WP Rewritely helps you manually rewrite your posts.
 * Version:     2.0.0
 * Author:      Big IM Toolbox
 * Author URI:  http://bigimtoolbox.com
 */

// Convenience methods
if(!class_exists('WPOOP')){class WPOOP{function hook($h){$p=10;$m=$this->sanitize_method($h);$b=func_get_args();unset($b[0]);foreach((array)$b as $a){if(is_int($a))$p=$a;else $m=$a;}return add_action($h,array($this,$m),$p,999);}private function sanitize_method($m){return str_replace(array('.','-'),array('_DOT_','_DASH_'),$m);}}}

require_once("views/settings.php");

/**
* wprewritely plugin 
*/
class wprewritely extends WPOOP
{

  /**
   * Holds the class instance
   */
  public static $instance;

  function __construct()
  {
    self::$instance = $this;
    $this->hook( 'save_post', 'onPostSave' , 10, 2);
    $this->hook( 'add_meta_boxes', 'onSetupPostMeta' );
    $this->hook( 'admin_footer', 'onInjectScript' );
  }

  /**
   * Returns settings for :param key
   *
   * @return string@bool
   * @author dz0ny
   **/
  public function option($key)
  {
    return get_option( strtolower(get_class($this)."_".$key), false );
  }

  /**
   * Returns current class as string
   *
   * @return string
   * @author dz0ny
   **/
  public function get_namespace()
  {
    return strtolower(get_class($this))."_";
  }

  /**
   * Injects script to the bottom of page, must be run last 
   *
   * @return void
   * @author dz0ny
   **/
  function onInjectScript(){
    ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        var fields = $("#wprewritely_plugin input[type=\"text\"]");
        $("#wprewritely_plugin a#clear").click(function() {
          fields.val("");
        });
        $("#wprewritely_plugin #save_placeholder").append($("input#publish").clone());
        $( "form" ).on( "submit", function( e ) {

          var valid = true;
          fileds.each(function(i, el) {
            if ($(el).val().match(/<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/)) {
              valid = false;
            }
          });
          
          if (!valid) {
            alert("WP Rewritely inputs should not contain any html!");
            return;
          }
        });
    });
    </script>
    <?php
  }


  /**
   * Renders metabox view
   *
   * @return void
   * @author
   **/

  public function renderMetaBox($post) {

    if (empty($post->post_content)) {
      echo "Content is not available yet.";
      return;
    }

    if (is_email($this->option("email"))) {

      $text = $post->post_content;

      $regex = "/<\/?\w+((\s+(\w|\w[\w-]*\w)(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/i";
      $ps = explode("\n", $text);

      $i = 0;
      $j = 0;

      ?>
      <div id="wprewritely_plugin">
      <?php

      foreach ($ps as $p) {
        if (strlen(strip_tags(trim($p)))) {

          echo '<h4>Paragraph #'.($i+1).'</h4>';
          $sentences = preg_split('/(?<=[.?!])\s+/', $p, -1, PREG_SPLIT_NO_EMPTY);
          if (count($sentences) > 1) {
            echo "<p><i>".strip_tags($p)."</i></p>";
          }

          foreach ($sentences as $s) {
            if (!strlen(trim($s))) continue;

            preg_match_all($regex, htmlspecialchars_decode($s), $match);
            $match = $match[0];
            if (!count($match)) $match = array("", "");
            $s = strip_tags(htmlspecialchars_decode($s));
            $n = $this->get_namespace();

            ?>

            <i><?=$s?></i>
            <input type="hidden" name="<?=$n?>field_default[<?=$i?>][<?=$j?>]" value="<?=$s?>" />
            <input type="hidden" name="<?=$n?>field_start[<?=$i?>][<?=$j?>]" value="<?=$match[0]?>" />
            <input type="hidden" name="<?=$n?>field_end[<?=$i?>][<?=$j?>]" value="<?=$match[1]?>" />
            <input type="text" name="<?=$n?>field[<?=$i?>][<?=$j?>]" style="width:100%" />
            <br /><br />

            <?php

            $j++;
          }
          $j = 0;
          $i++;
        }
      }

      ?>
        <div id="save_placeholder">
          <a class="button" id="clear">Clear</a>
        </div>
      </div><!-- end wprewritely_plugin -->
      <?php
    } else {
      echo('<b>WP Rewritely requires e-mail activation. <a href="'.get_admin_url().'/options-writing.php?#wprewritely_plugin">Click here!</a></b>');
    }
  }


  /**
   * Function overrides normal save method if plugin is used
   *
   * @return void
   * @author dz0ny
   **/
  function onPostSave($post_id){
    global $wpdb;

    //don't save if this is revision, when they are enabled
    if ( wp_is_post_revision( $post_id ) )
        return;

    // get meta about post
    if(is_object( $post_id )){
      $post = $post_id;
    } else {
      $post = get_post( $post_id );
    }

    // skip if posts or pages aren't enabled
    if ( empty($this->option("posts")) || empty($this->option("pages")) )
        return;

    $new_strings = $_POST[$this->get_namespace()."field"];
    $new_content = array();
    $should_update = false;
    if (isset($new_strings) && is_array($new_strings)) {
      $new_content = array();
      foreach ($new_strings as $paragraph_id => $paragraph) {

        $new_paragraph = array();

        foreach ($paragraph as $sentence_id => $sentence) {

          if (empty($sentence)){
            $sentence = $_POST[$this->get_namespace()."field_default"][$paragraph_id][$sentence_id];
          }else{
            $should_update |= true;
          }

          $start = $_POST[$this->get_namespace()."field_start"][$paragraph_id][$sentence_id];
          $end = $_POST[$this->get_namespace()."field_end"][$paragraph_id][$sentence_id];

          $new_paragraph[] = $start.$sentence.$end;
        }

        $new_content[] = implode(" ", $new_paragraph );
      }

      if (!$should_update) {
        return;
      }

      $result = $wpdb->update(
        $wpdb->posts,
        array( 'post_content' => implode("\n\n", $new_content)),
        array( 'ID' => $post->ID )
      );

    }

  }

  /**
   * Adds metabox to posts type edit screen
   *
   * @return void
   * @author 
   **/
   public function onSetupPostMeta() {

    if (!empty($this->option("posts"))) {
      add_meta_box(
        $this->get_namespace(),
        "WP Rewritely",
        array($this, 'renderMetaBox'),
        "post",
        "advanced",
        "high"
      );
    }


    if (!empty($this->option("pages"))) {
      add_meta_box(
        $this->get_namespace(),
        "WP Rewritely",
        array($this, 'renderMetaBox'),
        "page",
        "advanced",
        "high"
      );
    }

  }

}

add_action( 'init', create_function( '', 'new wprewritely;' ) );