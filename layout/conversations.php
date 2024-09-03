
<?php
if (count($onlineUsers) > 1 ) :
    foreach ($onlineUsers as $user):
        if($user->uuid !== $_SESSION['user']['uuid']):
?>
        <div id=<?php echo "c_id" . random_int(100, 10000); ?> data-token=<?php echo $user->token; ?> data-uuid=<?php echo $user->uuid; ?> class="chat-list-item">
            <img src="https://via.placeholder.com/40" alt="User">
            <div>
                <div><strong><?php echo $user->username ?></strong></div>
                <div class="text-muted small">Hey, how are you?</div>
            </div>
            <span class="online-indicator"></span>
        </div>
<?php
        endif;
    endforeach;
endif;
?>
<p class="p-2">Online users</p>