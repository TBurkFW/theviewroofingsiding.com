<?php

/**
 * Plugin Name: FaithWorks SMTP
 * Description: SMTP Settings for wp_mail()
 * Version: 1.0.0
 */

add_action('phpmailer_init', 'faithworks_smtp');
function faithworks_smtp($phpmailer)
{
    if (!is_object($phpmailer)) {
        $phpmailer = (object) $phpmailer;
    }
    $phpmailer->isSMTP(true);
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = SMTP_PORT;
    $phpmailer->Username   = SMTP_USER;
    $phpmailer->Password   = SMTP_PASS;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->FromName   = apply_filters('wp_mail_from_name', get_bloginfo('name'));
    $phpmailer->From       = apply_filters('wp_mail_from', SMTP_EMAIL);
    $phpmailer->SMTPDebug  = SMTP_DEBUG;
}
