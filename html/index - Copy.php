<?php
if(!isset($_FILES['msg'])){
    return; //not a post from our script
}

switch($_FILES['message']['error']){
    case UPLOAD_ERR_OK:
        move_uploaded_file($_FILES['msg']['tmp_name'], './home/kasadaka/Desktop/' . $_FILES['message']['name']);
        $prompt = 'Thanks, your message has been saved.';
        break;
    default:
        $prompt = 'Sorry, we could not save your message.';
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<vxml version="2.1">
    <form>
        <block>
            <prompt><?php echo $prompt ?></prompt>
        </block>
    </form>
</vxml>