<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Link Telegram Account</h3>
        </div>
        <div class="block-content">
            <?php if (isset($user_telegram) && $user_telegram['is_verified']): ?>
                <div class="alert alert-success" role="alert">
                    Your Telegram account is successfully linked! Your Chat ID: <strong><?= $user_telegram['telegram_chat_id'] ?></strong>
                </div>
                <p>You will now receive notifications via Telegram.</p>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    To link your Telegram account, please follow these steps:
                </div>
                <ol>
                    <li>Open Telegram and search for the bot: <strong>@YourSchoolBot</strong> (replace with actual bot username).</li>
                    <li>Start a chat with the bot.</li>
                    <li>Send the following verification token to the bot:</li>
                </ol>
                <div class="text-center mb-4">
                    <p class="fs-3 fw-bold text-primary"><?= $verification_token ?? '' ?></p>
                </div>
                <p>Once you send the token, your account will be linked automatically.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
