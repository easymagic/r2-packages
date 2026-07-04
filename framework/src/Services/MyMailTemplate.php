<?php

namespace R2Packages\Framework\Services;


class MyMailTemplate
{

    private function escape($value)
    {
        if ($value === null || $value === '') {
            return '&mdash;';
        }

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function formatAmount($amount)
    {
        if (is_numeric($amount)) {
            return '&#8358;' . number_format((float) $amount, 2);
        }

        if ($amount !== null && $amount !== '') {
            return $this->escape($amount);
        }

        return '&mdash;';
    }

    private function formatStatus($status)
    {
        if ($status === null || $status === '') {
            return '&mdash;';
        }

        return $this->escape(ucfirst(str_replace(array('_', '-'), ' ', $status)));
    }

    private function linkRow($link)
    {
        if ($link === null || $link === '') {
            return '';
        }

        $escaped = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');

        return '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Link</td>'
            . '<td style="padding:6px 0;color:#2563eb;word-break:break-all;">'
            . '<a href="' . $escaped . '" style="color:#2563eb;text-decoration:underline;">' . $escaped . '</a>'
            . '</td>'
            . '</tr>';
    }

    private function descriptionRow($description)
    {
        if ($description === null || $description === '') {
            return '';
        }

        return '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Description</td>'
            . '<td style="padding:6px 0;color:#111827;">' . nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) . '</td>'
            . '</tr>';
    }

    function notifyCustomer($name, $description, $link, $amount, $reference, $type, $status)
    {
        $nameHtml = htmlspecialchars(isset($name) ? $name : '', ENT_QUOTES, 'UTF-8');
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $typeHtml = $this->escape($type);
        $statusHtml = $this->formatStatus($status);
        $linkRow = $this->linkRow($link);
        $descriptionRow = $this->descriptionRow($description);

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>New order created</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#111827;">Your order was received</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'Thank you for placing an order with us. We have recorded it and will process it according to your order type. You can review the summary below.'
            . '</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Order summary</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Status</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $statusHtml . '</td>'
            . '</tr>'
            . $linkRow
            . $descriptionRow
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'If anything looks wrong, contact support and quote your reference.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function notifyBackendStaff($name, $description, $link, $amount, $reference, $type, $status)
    {
        $nameHtml = $this->escape($name);
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $typeHtml = $this->escape($type);
        $statusHtml = $this->formatStatus($status);
        $linkRow = $this->linkRow($link);
        $descriptionRow = $this->descriptionRow($description);

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>New order &mdash; staff notification</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#111827;">New order created</h1>'
            . '<p style="margin:0;color:#4b5563;">A customer has submitted a new order that may need review or fulfillment.</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0fdf4;border-radius:6px;border:1px solid #bbf7d0;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#166534;">Customer</p>'
            . '<p style="margin:0;font-size:16px;font-weight:600;color:#14532d;">' . $nameHtml . '</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Order details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Status</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $statusHtml . '</td>'
            . '</tr>'
            . $linkRow
            . $descriptionRow
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">Internal notification &mdash; do not forward to customers.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function notifyCustomerOfOrderChangedStatus(
        $name,
        $description,
        $link,
        $amount,
        $reference,
        $type,
        $status,
        $code
    ) {
        $nameHtml = $this->escape($name);
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $typeHtml = $this->escape($type);
        $statusHtml = $this->formatStatus($status);
        $linkRow = $this->linkRow($link);
        $descriptionRow = $this->descriptionRow($description);
        $codeHtml = $this->escape($code);
        $codeBlock = '';

        if ($code !== null && $code !== '') {
            $codeBlock = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:18px;background-color:#111827;border-radius:8px;border:1px solid #fcd34d;">'
                . '<tr>'
                . '<td style="padding:16px 18px;text-align:center;">'
                . '<p style="margin:0 0 8px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.05em;color:#fcd34d;">Pickup confirmation code</p>'
                . '<p style="margin:0;font-size:28px;font-weight:700;letter-spacing:0.12em;color:#ffffff;">' . $codeHtml . '</p>'
                . '</td>'
                . '</tr>'
                . '</table>';
        }

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Order status changed</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#111827;">Your order status was updated</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'There is a new update on your order. Please review the details below and keep your reference handy.'
            . '</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eff6ff;border-radius:6px;border:1px solid #bfdbfe;margin:0 0 16px 0;">'
            . '<tr>'
            . '<td style="padding:14px 18px;">'
            . '<p style="margin:0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#1d4ed8;">Current status</p>'
            . '<p style="margin:6px 0 0 0;font-size:18px;font-weight:700;color:#1e3a8a;">' . $statusHtml . '</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Order details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . $linkRow
            . $descriptionRow
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . $codeBlock
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'You can track progress from your dashboard. If you need help, contact support with your reference.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function notifyCustomerOfPriceAdjustment(
        $name,
        $description,
        $link,
        $amount,
        $reference,
        $type,
        $status,
        $payment_link
    ) {
        $nameHtml = $this->escape($name);
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $typeHtml = $this->escape($type);
        $statusHtml = $this->formatStatus($status);
        $linkRow = $this->linkRow($link);
        $descriptionRow = $this->descriptionRow($description);

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Price adjustment</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#111827;">Your order total was updated</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'We adjusted your order amount. Please review the updated summary and complete payment from your payment link.'
            . '</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fef3c7;border-radius:6px;border:1px solid #fcd34d;margin:0 0 16px 0;">'
            . '<tr>'
            . '<td style="padding:14px 18px;text-align:center;">'
            . '<p style="margin:0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#92400e;">Updated total</p>'
            . '<p style="margin:6px 0 0 0;font-size:22px;font-weight:700;color:#78350f;">' . $amountHtml . '</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Order details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Status</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $statusHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . $linkRow
            . $descriptionRow
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'If you have any questions about this adjustment, contact support and include your order reference.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function requestManualTopupNofication($name, $amount, $reference, $type, $source, $status)
    {
        $nameHtml = $this->escape($name);
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $typeHtml = $this->escape($type);
        $sourceHtml = $this->escape($source);
        $statusHtml = $this->formatStatus($status);

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Manual topup request received</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#111827;">Manual topup request submitted</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'We received your manual topup request and it is currently being reviewed by our team.'
            . '</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eff6ff;border-radius:6px;border:1px solid #bfdbfe;margin:0 0 16px 0;">'
            . '<tr>'
            . '<td style="padding:14px 18px;">'
            . '<p style="margin:0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#1d4ed8;">Current status</p>'
            . '<p style="margin:6px 0 0 0;font-size:18px;font-weight:700;color:#1e3a8a;">' . $statusHtml . '</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Topup details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Source</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $sourceHtml . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'You will receive another notification once your request has been approved or rejected.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function notifyApprovedManualTopup(
        $name,
        $amount,
        $balance,
        $reference,
        $type,
        $source,
        $status
    ) {
        $nameHtml = htmlspecialchars(isset($name) ? $name : '', ENT_QUOTES, 'UTF-8');
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $balanceHtml = $this->formatAmount($balance);
        $typeHtml = $this->escape($type);
        $sourceHtml = $this->escape($source);
        $statusHtml = $this->formatStatus($status);
        $balanceRow = '';

        if ($balance !== null && $balance !== '') {
            $balanceRow = '<tr>'
                . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Wallet balance</td>'
                . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $balanceHtml . '</td>'
                . '</tr>';
        }

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Manual wallet top-up approved</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);border-top:4px solid #16a34a;">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#14532d;">Your top-up was approved</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'Good news &mdash; your manual wallet top-up has been <strong>approved</strong>. The amount below has been credited to your wallet and you can use it right away.'
            . '</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 16px 0;background-color:#f0fdf4;border-radius:6px;border:1px solid #bbf7d0;">'
            . '<tr>'
            . '<td style="padding:14px 18px;">'
            . '<p style="margin:0;font-size:14px;color:#166534;line-height:1.5;">Thank you for your payment. If anything looks wrong, contact support and quote your reference.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Transaction details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount credited</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . $balanceRow
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Source</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $sourceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Status</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $statusHtml . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'Keep this email for your records. You can also view your wallet activity in your account when logged in.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }

    function notifyRejectedManualTopup(
        $name,
        $amount,
        $balance,
        $reference,
        $type,
        $source,
        $status,
        $reason
    ) {
        $nameHtml = htmlspecialchars(isset($name) ? $name : '', ENT_QUOTES, 'UTF-8');
        $referenceHtml = $this->escape($reference);
        $amountHtml = $this->formatAmount($amount);
        $balanceHtml = $this->formatAmount($balance);
        $typeHtml = $this->escape($type);
        $sourceHtml = $this->escape($source);
        $statusHtml = $this->formatStatus($status);
        $balanceRow = '';
        $reasonBlock = '';

        if ($balance !== null && $balance !== '') {
            $balanceRow = '<tr>'
                . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Wallet balance</td>'
                . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $balanceHtml . '</td>'
                . '</tr>';
        }

        if ($reason !== null && $reason !== '') {
            $reasonBlock = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 16px 0;background-color:#fef2f2;border-radius:6px;border:1px solid #fecaca;">'
                . '<tr>'
                . '<td style="padding:14px 18px;">'
                . '<p style="margin:0 0 6px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#b91c1c;">Reason provided</p>'
                . '<p style="margin:0;font-size:14px;color:#7f1d1d;line-height:1.5;">' . nl2br(htmlspecialchars($reason, ENT_QUOTES, 'UTF-8')) . '</p>'
                . '</td>'
                . '</tr>'
                . '</table>';
        }

        return '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Manual wallet top-up request not approved</title>'
            . '</head>'
            . '<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#1f2937;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:24px 12px;">'
            . '<tr>'
            . '<td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);border-top:4px solid #dc2626;">'
            . '<tr>'
            . '<td style="padding:28px 28px 8px 28px;">'
            . '<h1 style="margin:0 0 8px 0;font-size:20px;font-weight:600;color:#991b1b;">Your top-up request was not approved</h1>'
            . '<p style="margin:0;color:#4b5563;">Hi ' . $nameHtml . ',</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:8px 28px 20px 28px;">'
            . '<p style="margin:0 0 16px 0;color:#4b5563;">'
            . 'After reviewing your manual wallet top-up, we were unable to approve this request. <strong>No funds have been added</strong> to your wallet for this submission.'
            . '</p>'
            . $reasonBlock
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border-radius:6px;border:1px solid #e5e7eb;">'
            . '<tr>'
            . '<td style="padding:16px 18px;">'
            . '<p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280;">Request details</p>'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;width:40%;vertical-align:top;">Reference</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $referenceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Amount</td>'
            . '<td style="padding:6px 0;font-weight:600;color:#111827;">' . $amountHtml . '</td>'
            . '</tr>'
            . $balanceRow
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Type</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $typeHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Source</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $sourceHtml . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:6px 0;color:#6b7280;vertical-align:top;">Status</td>'
            . '<td style="padding:6px 0;color:#111827;">' . $statusHtml . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '<p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">'
            . 'If you believe this was a mistake, or you can provide clearer payment proof, please contact support and quote your reference above.'
            . '</p>'
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td style="padding:16px 28px 28px 28px;border-top:1px solid #e5e7eb;">'
            . '<p style="margin:0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</body>'
            . '</html>';
    }
}
