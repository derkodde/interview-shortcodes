<?php

function isshort_adminpage_bootstrap($hook) {
    if ( 'plugins_page_isshort-options' != $hook ) {
        return;
    }

        wp_register_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
        wp_enqueue_script( 'bootstrap-js');
        wp_register_style('bootstrap-admin', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-admin');
        wp_register_style('isshort-admin',  plugin_dir_url( __FILE__ ) . '/admin.css' , array('bootstrap-admin'));
        wp_enqueue_style('isshort-admin', array('bootstrap-admin'));
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
    <h2>Interview Styles Shortcode - Options</h2>
    <h3>Settings</h3>

    <?php

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
            <div class="row">
            <div class="well">
            <h3>Gespeicherte Werte</h3><br/ >
                <h4><?php echo $currShortcodeTitle; ?></h4>

                <?php
            foreach ($newVal as $key => $value) { ?>
                <div class="col col-xs-3"> <p><?php echo $key ; ?> </p>  </div>
                <div class="col col-xs-1"><p>=></p></div>
                <div class="col col-xs-8"><p><?php echo $value ?> </p></div>

                 <?php
                updateShortcodeOption ($value , $currShortcode,'attr', $key ,'value' );
            }
            echo '<div style="clear:both"></div></div></div>';

            wp_die('<div class="updated"><p><strong>Shortcode gespeichert</strong></p><a href="" >zurück</a></div>');
        }

        ?>
        <div class="well">
            <form class="form-horizontal" name="isshort_options" method="post" action="">
                <?php
                // echo "isshort_options : ";
                // print_r($isshort_options);
                // echo "<br/> shortcode :   ";
                // print_r($shortcode=$isshort_options[$key]['shortcode']);
                $shortcode=$isshort_options[$key]['shortcode'];
                //
                // echo "<br/> options-array : ";
                // print_r($isshort_shortcode_options= $isshort_options[$key]);
                $isshort_shortcode_options= $isshort_options[$key];

                ?>

                <!-- Caption formgroup -->
                <h4><?php echo $isshort_shortcode_options['title'];?></h4>

                <?php foreach ($isshort_shortcode_options['attr'] as $key => $value): ?>
                <div class="form-group ">
                    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="<?php echo $shortcode; ?>">

                    <label class="col-sm-2 control-label" for="<?php echo $key; ?>"><?php echo $value['title']; ?>:</label>
                    <div class="col-sm-6 ">
                        <input class="form-control" type="text"  name="<?php echo $key; ?>" value="<?php echo $value['value']; ?>" >
                    </div>

                    <div class="col-sm-4">
                        <code>[cap <?php echo $value['shortcode']; ?>="<?php echo $value['value']; ?>"]S.K.[/cap]</code>
                    </div>
                </div><!--form-group-->
                <?php endforeach; ?>
                <hr />

                <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
                </p>
            </form>
        </div>
    <?php } ?>

</div>
<?php
//
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
