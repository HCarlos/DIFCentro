<?php
require 'Mandrill.php';

$mandrill = new Mandrill(); 

// If are not using environment variables to specific your API key, use:
// $mandrill = new Mandrill("YOUR_API_KEY")

$message = array(
    'subject' => 'Test message',
    'from_email' => 'manager@logydes.com.mx',
    'html' => '<p>this is a test message with Mandrill\'s PHP wrapper!.</p>',
    'to' => array(array('email' => 'devch@arji.edu.mx', 'name' => 'Recipient 1')),
    'merge_vars' => array(array(
        'rcpt' => 'manager@logydes.com.mx',
        'vars' =>
        array(
            array(
                'name' => 'FIRSTNAME',
                'content' => 'Recipient 1 first name'),
            array(
                'name' => 'LASTNAME',
                'content' => 'Last name')
    ))));

$template_name = 'Stationary';

$template_content = array(
    array(
        'name' => 'main',
        'content' => 'Hi *|FIRSTNAME|* *|LASTNAME|*, thanks for signing up.'),
    array(
        'name' => 'footer',
        'content' => 'Copyright 2014.')

);

print_r($mandrill->messages->sendTemplate($template_name, $template_content, $message));

?>