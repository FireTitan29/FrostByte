<!-- Prevent direct access to this file unless APP_RUNNING is defined -->
<?php
if (!defined('APP_RUNNING')) {
    header("Location: ../../index.php");
    exit;
}
?>

<!-- Renders a single message bubble in the chat window
     - Adds a date line if the day changes ($addDateLine, $date)
     - Displays message text ($textBody) and timestamp ($timeStamp)
     - Messages sent by the current user ($sent = true) are styled in blue, aligned right
     - Messages received are styled in pink, aligned left -->
 
<?php if ($addDateLine): ?>
    <div class="date-holder-messages">
        <span><?php echo $date ?></span>
    </div>
<?php endif; ?>

<?php if ($sent): ?>
        <div class="message-sent-holder">
            <div>
                <span class="message message-sent"><?php echo $textBody ?></span>
                <span class="timestamp-message timesent"><?php echo $timeStamp ?></span>
            </div>
        </div>
<?php else: ?>
        <div class="message-recieved-holder">
            <div>
                <span class="message message-recieved"><?php echo $textBody ?></span>
                <span class="timestamp-message timerecieved"><?php echo $timeStamp?></span>
            </div>
        </div>
<?php endif; ?>

