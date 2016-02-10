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

    // variables for the field and option names
    $option_name = "isshort_cap_options";
    $hidden_field_name = $option_name.'_hidden';

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $newVal = $_POST;
        array_shift($newVal);
        array_pop($newVal);
        // print_r($newVal);
        // Save the posted value in the database
        foreach ($newVal as $key => $value) {
            updateShortcodeOption($value,'cap','attr',$key,'value');
        }

        // Put a "settings saved" message on the screen
        ?>
        <div class="updated"><p><strong>gespeichert</strong></p></div>
        <?php

    }
    $isshort_caption_options = getShortcodeOptions('cap');

    // Now display the settings editing screen

    echo '<div class="wrap ">';

    // header
    ?>

    <h2>Interview Styles Shortcode - Options</h2>

        <h3>Settings</h3>
    <form class="form-horizontal" name="isshort_options" method="post" action="">

        <h4><?php echo $isshort_caption_options['title'];?></h4>
    <?php foreach ($isshort_caption_options['attr'] as $key => $value): ?>
        <div class="form-group ">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

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
</div><!--wrap-->
<?php

// setCaptionDefaults ();
}


// structure of the options array
function setCaptionDefaults (){

    $isshort_caption_options = array(
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
    );

    update_option( 'isshort_cap_options', $isshort_caption_options );
}

function setQuestionDefaults (){

}
function setAnswerDefaults (){

}

// Params=> ('shortcode', 'args'(siehe function set*Defaults() )
function getShortcodeOptions () {

    // all insertet args as keys of array
    $args = func_num_args();
    $arrArgs = func_get_args();

    // from DB
    $db_option_name = "isshort_".$arrArgs['0']."_options";
    array_shift($arrArgs);
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

    //update value
    $newVal=array_shift($arrArgs);
    // print_r($arrArgs);
    // echo "<br/>";
    // get old array from DB
    $db_option_name = "isshort_".$arrArgs['0']."_options";
    $shortcode_option_array = get_option(  $db_option_name  );
    $shortcode=array_shift($arrArgs);
    // print_r($shortcode);
    // put new value into options-array
    // $newVal_dest = '';
    $newVal_dest = '$shortcode_option_array';

    foreach ($arrArgs as $key => $value) {
        $newVal_dest = $newVal_dest."['".$value."']";
    }
    eval("$newVal_dest='$newVal';");
    // print_r($shortcode_option_array);

    update_option( $db_option_name , $shortcode_option_array);
}
