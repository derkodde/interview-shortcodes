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

    ?>

     <!-- Now display the settings editing screen -->
<div class="wrap ">
    <h1>Interview Styles Shortcode - Options</h1>
    <?php
    //get Array
    $isshort_options = getShortcodeOptions();
    //submit action

    $isshort_options = getShortcodeOptions();
    foreach ($isshort_options as $key => $value) {
        $hidden_field_name = $key.'_hidden';
        $currShortcodeTitle = $isshort_options[$key]['title'];
        $currShortcode= $isshort_options[$key]['shortcode'];

        // See if the user has posted us some information

        // If they did, this hidden field will be set to the shortcode name
        if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == $key ) {
            // Read their posted value
            $newVal = $_POST;
            array_shift($newVal);
            array_pop($newVal);

            foreach ($newVal as $key => $value) {
                updateShortcodeOption ($value , $currShortcode,'attr', $key ,'value' );
            }

            ?>
            <div id="message" class="updated notice is-dismissible"><?php echo $currShortcodeTitle; ?> Shortcode gespeichert</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Diese Meldung verwerfen.</span></button></div><?php
        }
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
                    <p>zum Beispiel: <code>[cap]T[/cap]est</code></p>
        			<p>Nutze die Macht der MCE Buttons!</p>
                    <img src="">
    			</div>
    	<div class="welcome-panel-column">
    		<h3>Attribute verwenden</h3>
    		 <ul>
                 <li><code>[shortcode attribute="value"]text[/shortcode]</code></li>
                 <li>Use source code view to get class names for <a href="http://ianlunn.github.io/Hover/" target="_blank">Hover.CSS </a></li>
				<li>gültige Farbwerte <a href="http://www.w3schools.com/colors/colors_hex.asp" target="_blank">http://www.w3schools.com/</a></li>
    		</ul>
    	</div>
    	<div class="welcome-panel-column welcome-panel-last">
    		<!-- <h3>Links</h3> -->
    		<ul>
                <li><a href="http://localhost/wordpress/" class="welcome-icon welcome-view-site">Sieh dir deine Website an</a></li>
                <li><a class="button button-primary button-hero load-customize hide-if-no-customize" href="/wp-admin/edit.php">Beiträge bearbeiten</a></li>
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
                        <label for="<?php echo $key; ?>"><?php echo $value['title']; ?>:</label><br />
                        <code>[<?php echo $shortcode;?> <?php echo $value['shortcode']; ?>="<?php echo $value['value']; ?>"]S.K.[/<?php echo $shortcode;?>]</code>
                    </th>
                    <td>
                            <input class="form-control" type="text"  name="<?php echo $key; ?>" value="<?php echo $value['value']; ?>" >
                    <td>
                        <label><?php echo $value['desc']; ?></label>
                    <td>
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
                'desc'=> 'Sets the Background color of the Caption - accepts CSS values',
            ),
            'style' => array(
                'shortcode' => 'style',
                'title' => 'Style',
                'value' => 'square',
                'desc'=> 'Choose <code>circle</code> or <code>square</code>',
            ),
            'text-color'=> array(
                'shortcode' => 'text-color',
                'title' => 'Font-color',
                'value' => 'white',
                'desc'=> 'Color of the Font - accepts CSS values',
            ),
		    'size' => array(
                'shortcode' => 'size',
                'title' => 'Width and Height',
                'value' => '4em',
                'desc'=> 'Size of the Caption (default = 4em) - accepts CSS values',
            ),
            'font-family' => array(
                'shortcode' => 'font-family',
                'title' => 'Font-Family',
                'value' => 'Georgia',
                'desc'=> 'simple font family CSS values (default = Georgia)',
            ),
            'hover' => array(
                'shortcode' => 'hover',
                'title' => 'Hover style',
                'value' => 'hvr-buzz-out',
                'desc'=> 'Hover.css classes (example: "hvr-buzz-out")',
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
                'desc'=> 'Sets the Background color of the Question (default is <code>#eee</code>) - accepts CSS values',
            ),
            'corner' => array(
                'shortcode' => 'corner',
                'title' => 'Corner of Tip',
                'value' => 'top-left',
                'desc'=> 'Sets the corner of the tip (default is <code>top-left</code> - accepts <code>top-left, top-right, bottom-left, bottom-right)',
            ),
            'radius'  => array(
                'shortcode' => 'radius',
                'title' => 'Corner-radius',
                'value' => '5px',
                'desc'=> 'Sets the radius of the edges - accepts CSS values for border-radius (default: <code>5px</code>) ',
            ),
            'text-color'=> array(
                'shortcode' => 'text-color',
                'title' => 'Font Color',
                'value' => 'grey',
                'desc'=> 'Color of the Font - accepts CSS values',
            ),
            'hover' =>  array(
                'shortcode' => 'hover',
                'title' => 'HVR Effect',
                'value' => 'hvr-shrink',
                'desc'=> 'Hover.css classes (example: <code>hvr-buzz-out</code>)',
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
                'desc'=> 'Sets the Background color of the Answer (default is <code>#DCF8C6</code>) - accepts CSS values',
            ),
            'corner' => array(
                'shortcode' => 'corner',
                'title' => 'Corner of Tip',
                'value' => 'bottom-right',
                'desc'=> 'Sets the corner of the tip (default is <code>bottom-right</code> for questions) - accepts <code>top-left, top-right, bottom-left, bottom-right)',
            ),
            'radius'  => array(
                'shortcode' => 'radius',
                'title' => 'Corner-radius',
                'value' => '5px',
                'desc'=> 'Sets the radius of the edges - accepts CSS values for border-radius (default: <code>5px</code>) ',
            ),
            'text-color' => array(
                'shortcode' => 'text-color',
                'title' => 'Font Color',
                'value' => 'grey',
                'desc'=>  'Color of the Font - accepts CSS values',
            ),
            'hover' =>  array(
                'shortcode' => 'hover',
                'title' => 'HVR Effect',
                'value' => 'hvr-shrink',
                'desc'=>  'Hover.css classes (example: <code>hvr-buzz-out</code>)',
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

    //cut off te new value
    $newVal=array_shift($arrArgs);

    //cut off the shortcode
    $shortcode=array_shift($arrArgs);

    // get old array from DB
    $shortcode_option_array = get_option(  'isshort_options'  );
    $db_option= 'isshort_options';
    // adress the right shortcode branch of the options-array
    $newVal_dest = '$shortcode_option_array';
    $newVal_dest=$newVal_dest."['".$shortcode."']";

    //go deeper into the branches
    foreach ($arrArgs as $key => $value) {
        $newVal_dest = $newVal_dest."['".$value."']";
    }
    //$newVal_dest stores the branch name.
    eval("$newVal_dest='$newVal';");

    update_option( $db_option , $shortcode_option_array);
}
