<?php
$templateUser = null;

if (isset($baseUserService) && is_object($baseUserService)) {
    if (isset($baseUserService->baseUserEntity) && is_object($baseUserService->baseUserEntity)) {
        $templateUser = $baseUserService->baseUserEntity;
    } else {
        $templateUser = $baseUserService;
    }
}

$templateName = isset($templateUser->name) && !empty($templateUser->name) ? $templateUser->name : 'there';
$templateId = isset($templateUser->id) ? $templateUser->id : '';
$templateEmail = isset($templateUser->email) ? $templateUser->email : '';
$templateOtp = isset($templateUser->otp) ? $templateUser->otp : '';

$templateNameEscaped = htmlspecialchars($templateName, ENT_QUOTES, 'UTF-8');
$templateIdEscaped = htmlspecialchars($templateId, ENT_QUOTES, 'UTF-8');
$templateEmailEscaped = htmlspecialchars($templateEmail, ENT_QUOTES, 'UTF-8');
$templateOtpEscaped = htmlspecialchars($templateOtp, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete your registration</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f6f8; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:32px 32px 16px 32px;">
                            <h1 style="margin:0; font-size:24px; line-height:32px; color:#111827;">Welcome, <?php echo $templateNameEscaped; ?></h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 16px 32px; font-size:15px; line-height:24px;">
                            <p style="margin:0 0 16px 0;">Thanks for creating your account<?php echo !empty($templateEmailEscaped) ? ' with ' . $templateEmailEscaped : ''; ?>.</p>
                            <p style="margin:0;">Use the verification code below to activate your account.</p>
                            <?php if (!empty($templateIdEscaped)) { ?>
                                <p style="margin:12px 0 0 0;">User ID: <strong><?php echo $templateIdEscaped; ?></strong></p>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:8px 32px 24px 32px;">
                            <div style="display:inline-block; padding:16px 28px; background-color:#eef2ff; border-radius:8px; color:#3730a3; font-size:28px; line-height:36px; letter-spacing:6px; font-weight:bold;">
                                <?php echo $templateOtpEscaped; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; font-size:12px; line-height:18px; color:#6b7280;">
                            <p style="margin:0;">If you did not create this account, you can ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
