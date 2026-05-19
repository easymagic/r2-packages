<?php

namespace R2Packages\Framework\mail_templates;

use R2Packages\Framework\Entities\BaseUserEntity;

class MailTemplates
{
    private function resolveTemplateVars(BaseUserEntity $baseUserEntity)
    {
        $name = isset($baseUserEntity->name) && !empty($baseUserEntity->name) ? $baseUserEntity->name : 'there';
        $id = isset($baseUserEntity->id) ? $baseUserEntity->id : '';
        $email = isset($baseUserEntity->email) ? $baseUserEntity->email : '';
        $otp = isset($baseUserEntity->otp) ? $baseUserEntity->otp : '';

        return array(
            'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'id' => htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            'otp' => htmlspecialchars($otp, ENT_QUOTES, 'UTF-8'),
        );
    }

    public function registration(BaseUserEntity $baseUserEntity)
    {
        $v = $this->resolveTemplateVars($baseUserEntity);
        $emailSuffix = !empty($v['email']) ? ' with ' . $v['email'] : '';
        $userIdBlock = !empty($v['id'])
            ? '<p style="margin:12px 0 0 0;">User ID: <strong>' . $v['id'] . '</strong></p>'
            : '';

        return '<!DOCTYPE html>
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
                            <h1 style="margin:0; font-size:24px; line-height:32px; color:#111827;">Welcome, ' . $v['name'] . '</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 16px 32px; font-size:15px; line-height:24px;">
                            <p style="margin:0 0 16px 0;">Thanks for creating your account' . $emailSuffix . '.</p>
                            <p style="margin:0;">Use the verification code below to activate your account.</p>
                            ' . $userIdBlock . '
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:8px 32px 24px 32px;">
                            <div style="display:inline-block; padding:16px 28px; background-color:#eef2ff; border-radius:8px; color:#3730a3; font-size:28px; line-height:36px; letter-spacing:6px; font-weight:bold;">
                                ' . $v['otp'] . '
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
</html>';
    }

    public function passwordResetRequest(BaseUserEntity $baseUserEntity)
    {
        $v = $this->resolveTemplateVars($baseUserEntity);
        $emailSuffix = !empty($v['email']) ? ' (' . $v['email'] . ')' : '';
        $userIdBlock = !empty($v['id'])
            ? '<p style="margin:12px 0 0 0;">User ID: <strong>' . $v['id'] . '</strong></p>'
            : '';

        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1f2933;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f6f8; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:32px 32px 16px 32px;">
                            <h1 style="margin:0; font-size:24px; line-height:32px; color:#111827;">Reset your password</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 16px 32px; font-size:15px; line-height:24px;">
                            <p style="margin:0 0 16px 0;">Hi ' . $v['name'] . ',</p>
                            <p style="margin:0;">We received a request to reset the password for your account' . $emailSuffix . '.</p>
                            ' . $userIdBlock . '
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:8px 32px 24px 32px;">
                            <div style="display:inline-block; padding:16px 28px; background-color:#fef3c7; border-radius:8px; color:#92400e; font-size:28px; line-height:36px; letter-spacing:6px; font-weight:bold;">
                                ' . $v['otp'] . '
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 32px 32px; font-size:15px; line-height:24px;">
                            <p style="margin:0;">Use this code in the app to confirm your password reset.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; font-size:12px; line-height:18px; color:#6b7280;">
                            <p style="margin:0;">If you did not request a password reset, you can ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    public function verifyOtp()
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
Lorem ipsum dolor sit amet consectetur adipisicing elit. At adipisci deleniti dolorem qui id debitis architecto, nesciunt sequi alias magni nisi nulla quos. Natus illo eaque aperiam nam vel fuga.
</body>
</html>';
    }
}
