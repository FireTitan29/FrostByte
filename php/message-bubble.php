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

