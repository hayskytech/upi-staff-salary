<?php
/* -- User can edit his own profile from dashboard with these lines. -- */
add_action( 'personal_options_update', 'save_extra_user_profile_fields_odl' );
add_action( 'show_user_profile', 'extra_user_profile_fields_odl' );

/* -- ADMIN can edit all user profiles from dashboard with these lines. -- */
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields_odl' );
add_action( 'edit_user_profile', 'extra_user_profile_fields_odl' );

/* -- Actual Code starts here -- */
function save_extra_user_profile_fields_odl( $user_id ) {
    if(!current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta($user_id, 'upi_id', $_POST["upi_id"]);
    update_user_meta($user_id, 'fullname', $_POST["fullname"]);
    update_user_meta($user_id, 'amount', $_POST["amount"]);
}


function extra_user_profile_fields_odl( $user ) { 
    $user_id = $user->ID;
    ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.js"></script><!-- -- Semantic-UI CSS & JS files included here -- -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/button.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.min.css">
<style type="text/css">
    div.ui.dropdown{
        min-height: 1em !important;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.js"></script>

    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <td>Upi Id</td>
            <td><input type="text" name="upi_id" >
            </td>
        </tr>
        <tr>
            <td>Fullname</td>
            <td><input type="text" name="fullname" >
            </td>
        </tr>
        <tr>
            <td>Amount</td>
            <td><input type="text" name="amount" >
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        $('input').addClass('regular-text');
        $('input[name=upi_id]').val('<?php echo get_the_author_meta('upi_id', $user->ID); ?>');
        $('input[name=fullname]').val('<?php echo get_the_author_meta('fullname', $user->ID); ?>');
        $('input[name=amount]').val('<?php echo get_the_author_meta('amount', $user->ID); ?>');
        $(".ui.dropdown").dropdown();
        // Hide some default options //
            /*
            $('.user-url-wrap').hide();
            $('.user-description-wrap').hide();
            $('.user-profile-picture').hide();
            $('.user-rich-editing-wrap').hide();
            $('.user-admin-color-wrap').hide();
            $('.user-comment-shortcuts-wrap').hide();
            $('.show-admin-bar').hide();
            $('.user-language-wrap').hide();
            //*/
    </script>
<?php 
}

/* -- Add extra columns to "Users Lists" in Admin Dashboard -- */
add_filter( 'manage_users_columns', 'new_modify_user_table_odl' );
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row_odl', 10, 3 );

function new_modify_user_table_odl( $column ) {
    $column['upi_id'] = 'Upi Id';
    $column['fullname'] = 'Fullname';
    $column['amount'] = 'Amount';
    $column['qrcode'] = 'QR code';
    return $column;
}
function new_modify_user_table_row_odl( $val, $column_name, $user_id ) {
    $meta = get_user_meta($user_id);
    switch ($column_name) {
        case 'upi_id' :
            return $meta['upi_id'][0];
        case 'fullname' :
            return $meta['fullname'][0];
        case 'amount' :
            return $meta['amount'][0];
        case 'qrcode' :
            return '
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<button onclick="run(\'qr'.$user_id.'\')" type="button">Show QR Code</button>
<div id="qr'.$user_id.'" style="display:none;padding:5px"></div>
<script type="text/javascript">
function run(id){
    $("#"+id).toggle();
}

var x = new QRCode("qr'.$user_id.'", {
    text: "upi://pay?pa=' . $meta['upi_id'][0] . '&pn=' . $meta['fullname'][0] . '&cu=INR&am=' . $meta['amount'][0] . '",
    width: 128,
    height: 128,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
});
x.makeCode()
</script>';
        default:
    }
    return $val;
}
?>
<?php
/* Powered By Haysky Code Generator: KEY
[["text","upi_id"],["text","fullname"],["text","amount"],["submit","User Extra"]]
*/
?>