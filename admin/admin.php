<?php

function isshort_adminpage_bootstrap($hook) {
    if ( 'plugins_page_isshort-options' != $hook ) {
        return;
    }

        wp_register_style('isshort-admin',  plugin_dir_url( __FILE__ ) . '/admin.css' );
        wp_enqueue_style('isshort-admin');
}
add_action( 'admin_enqueue_scripts', 'isshort_adminpage_bootstrap' );


function isshort_adminpage() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    //get Array
    $isshort_options = getShortcodeOptions();
    //submit action
    ?>

     <!-- Now display the settings editing screen -->
<div class="wrap ">
    <h1>Interview Styles Shortcode - Options</h1>
    <?php

    $isshort_options = getShortcodeOptions();
    foreach ($isshort_options as $key => $value) {
        $hidden_field_name = $key.'_hidden';
        $currShortcodeTitle = $isshort_options[$key]['title'];
        $currShortcode= $isshort_options[$key]['shortcode'];
        // See if the user has posted us some information
        // If they did, this hidden field will be set to 'Y'
        if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == $key ) {
            // Read their posted value
            $newVal = $_POST;
            array_shift($newVal);
            array_pop($newVal);

            // Save the posted value in the database and Put a "settings saved" message on the screen
            ?>
            <!-- <h2>Gespeicherte Werte</h2>
            <h3><?php //echo $currShortcodeTitle; ?></h3> -->
            <!-- <div class="postbox-container"><div class="postbox"><div class="inside">
            <table class="form-table">
            <tbody> -->
            <?php
            foreach ($newVal as $key => $value) {       ?>
                <!-- <tr>
                <th scope="row"><label>
                <?php// echo $key ; ?></label>
            </th>
            <td>
            <?php //echo $value ?>
                    </td>
                </tr> -->
                <?php
                updateShortcodeOption ($value , $currShortcode,'attr', $key ,'value' );
            }
            ?>
            <!-- </tbody>
            </table>
            </div></div></div>
            <div style="clear:both"></div> -->

            <div class="updated"><p><strong><?php echo $currShortcodeTitle; ?> Shortcode gespeichert</strong></p></div>


    <?php  }
    }

?>




    <div id="welcome-panel" class="welcome-panel">
    	<!-- <a class="welcome-panel-close" href="http://localhost/wordpress/wp-admin/?welcome=0">Verwerfen</a> -->
    			<div class="welcome-panel-content">
    	<h2>How To</h2>
    	<p class="about-description">Wir haben einige Links zusammengestellt, um dir den Start zu erleichtern:</p>
    	<div class="welcome-panel-column-container">
    	<div class="welcome-panel-column">
    					<h3>Wie funktionieren shortcodes?</h3>
        			<!-- <a class="button button-primary button-hero load-customize hide-if-no-customize" href="http://localhost/wordpress/wp-admin/customize.php">Website anpassen</a>
        				<a class="button button-primary button-hero hide-if-customize" href="http://localhost/wordpress/wp-admin/themes.php">Website anpassen</a>
        					<p class="hide-if-no-customize">oder <a href="http://localhost/wordpress/wp-admin/themes.php">das komplette Theme wechseln</a></p> -->
    			</div>
    	<div class="welcome-panel-column">
    		<h3>Attribute verwenden</h3>
    		 <ul>
    					<!-- <li><a href="http://localhost/wordpress/wp-admin/post-new.php" class="welcome-icon welcome-write-blog">Schreib deinen ersten Beitrag</a></li> -->
    			<!-- <li><a href="http://localhost/wordpress/wp-admin/post-new.php?post_type=page" class="welcome-icon welcome-add-page">Erstelle eine "Über mich"-Seite</a></li> -->
    		</ul>
    	</div>
    	<div class="welcome-panel-column welcome-panel-last">
    		<h3>Links</h3>
    		<ul>
                <li><a href="http://localhost/wordpress/" class="welcome-icon welcome-view-site">Sieh dir deine Website an</a></li>
				<li><a href="http://ianlunn.github.io/Hover/" target="_blank" class="welcome-icon welcome-learn-more">Erfahre mehr über Hover.CSS </a></li>
    		</ul>
    	</div>
    	</div>
    	</div>
    		</div>

            <?php
            $isshort_options = getShortcodeOptions();





    //loop the shortcodes
           foreach ($isshort_options as $key => $value) {
                 $shortcode=$isshort_options[$key]['shortcode'];
                $isshort_shortcode_options= $isshort_options[$key];
                ?>

        <div class="postbox-container"><div class="postbox"><div class="inside">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><h3><?php echo $isshort_shortcode_options['title'];?></h3></th>
                    </tr>

                <?php
        //loop the shortcode settings
        foreach ($isshort_shortcode_options['attr'] as $key => $value): ?>
            <form class="form-horizontal" name="isshort_options" method="post" action="">
                <tr>
                    <input type="hidden" name="<?php echo $shortcode."_hidden" ?>" value="<?php echo $shortcode; ?>">

                    <th>
                        <label for="<?php echo $key; ?>"><?php echo $value['title']; ?>:</label>
                    <th>
                    <td>
                            <input class="form-control" type="text"  name="<?php echo $key; ?>" value="<?php echo $value['value']; ?>" >
                    <td>
                    <td>
                    <code>[<?php echo $shortcode;?> <?php echo $value['shortcode']; ?>="<?php echo $value['value']; ?>"]S.K.[/<?php echo $shortcode;?>]</code>
                    </td><!--form-group-->
                </tr>
            <?php endforeach; //end of shortcode setting?>

                <tr>
                    <td>
                        <p class="submit">
                            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
                        </p>
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
</div></div></div>
<div class="clear"></div>
    <?php }// end of shortcode loop?>
</div><!--wrap-->

<?php


// setOptionDefaults ();

}


// structure of the options array
function setOptionDefaults () {

$isshort_options  = array(
    'cap' => array(
        'shortcode' => 'cap',
        'title' => 'Captions',
        'attr' => array(
            'color' => array(
                'shortcode' => 'color',
                'title' => 'Background-color',
                'value' => 'grey',
            ),
            'style' => array(
                'shortcode' => 'style',
                'title' => 'Style',
                'value' => 'square',
            ),
            'text-color'=> array(
                'shortcode' => 'text-color',
                'title' => 'Font-color',
                'value' => 'white',
            ),
		    'size' => array(
                'shortcode' => 'size',
                'title' => 'Width and Height',
                'value' => '4em',
            ),
            'font-family' => array(
                'shortcode' => 'font-family',
                'title' => 'Font-Family',
                'value' => 'Georgia',
            ),
            'hover' => array(
                'shortcode' => 'hover',
                'title' => 'Hover style',
                'value' => 'hvr-buzz-out',
            ),
        ),
    ),
    'question'=> array(
        'shortcode' => 'question',
        'title' => 'Question',
        'attr' => array(
            'color' => array(
                'shortcode' => 'color',
                'title' => 'Background-color',
                'value' => '#eee',
            ),
            'corner' => array(
                'shortcode' => 'corner',
                'title' => 'Corner of Tip',
                'value' => 'top-left',
            ),
            'radius'  => array(
                'shortcode' => 'radius',
                'title' => 'Corner-radius',
                'value' => '5px',
            ),
            'text-color'=> array(
                'shortcode' => 'text-color',
                'title' => 'Font Color',
                'value' => 'grey',
            ),
            'hover' =>  array(
                'shortcode' => 'hover',
                'title' => 'HVR Effect',
                'value' => 'hvr-shrink',
            ),
        ),
    ),
    'answer'=> array(
        'shortcode' => 'answer',
        'title' => 'Answer',
        'attr' => array(
            'color' => array(
                'shortcode' => 'color',
                'title' => 'Background-color',
                'value' => '#DCF8C6',
            ),
            'corner' => array(
                'shortcode' => 'corner',
                'title' => 'Corner of Tip',
                'value' => 'bottom-right',
            ),
            'radius'  => array(
                'shortcode' => 'radius',
                'title' => 'Corner-radius',
                'value' => '5px',
            ),
            'text-color' => array(
                'shortcode' => 'text-color',
                'title' => 'Font Color',
                'value' => 'grey',
            ),
            'hover' =>  array(
                'shortcode' => 'hover',
                'title' => 'HVR Effect',
                'value' => 'hvr-shrink',
            ),
        ),
    ),
);

update_option( 'isshort_options', $isshort_options );
}


// Params=> ( 'args'(siehe function set*Defaults() )
function getShortcodeOptions () {

    // all insertet args as keys of array
    $args = func_num_args();
    $arrArgs = func_get_args();

    // from DB
    $db_option_name = "isshort_options";
    // array_shift($arrArgs);
    $sc_array = get_option(  $db_option_name );

    $result=$sc_array;

    foreach ($arrArgs as $key => $value) {
        $result=$result[$value];
    }

    return $result;
}


// Params=> ('newVal', 'shortcode', 'args'(siehe function set*Defaults() )
function updateShortcodeOption () {

    // get arguments to update
    $args = func_num_args();
    $arrArgs = func_get_args();
    // print_r($arrArgs);
    //update value
    $newVal=array_shift($arrArgs);
    // print_r($newVal);
    // print_r($arrArgs);
    // echo "<br/>";
    // get old array from DB
    $db_option = "isshort_options";
    // print_r($shortcode_option_array = get_option(  $db_option  ));
    $shortcode_option_array = get_option(  $db_option  );

    $shortcode=array_shift($arrArgs);

    // put new value into options-array
    // $newVal_dest = '';
    $newVal_dest = '$shortcode_option_array';
    $newVal_dest=$newVal_dest."['".$shortcode."']";

    foreach ($arrArgs as $key => $value) {
        $newVal_dest = $newVal_dest."['".$value."']";
    }
    // print_r($newVal_dest . $newVal );
    eval("$newVal_dest='$newVal';");
    // print_r($shortcode_option_array);

    update_option( $db_option , $shortcode_option_array);
}
