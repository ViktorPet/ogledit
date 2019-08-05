<?php
namespace Application\Helper;

use Zend\Mail\Exception\RuntimeException;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * Class Mail
 * @package Armenio\Mail
 */
class Mail {
    CONST OGLEDI_MAIL_1 = 'info@ogledi.bg';
    CONST OGLEDI_MAIL_2 = 'info@ogledi.bg';
    CONST OGLEDI_WEBSITE = 'https://ogledi.bg/';

    /**
     * @param array $config
     * @return bool
     */
    public static function send($config = []) {       
        $transport = new SmtpTransport(new SmtpOptions([
            'name' => 'pengo.cmailpro.net',
            'host' => 'pengo.cmailpro.net',
            'port' => '26',
            'connection_class' => 'login',
            'connection_config' => [
                'username' => 'noreply@ogledi.bg',
                'password' => 'Ogledi180'
            ],
        ]));

        $message = new Message();
        $message->setEncoding('UTF-8');
        $message->setFrom('noreply@ogledi.bg', 'noreply@ogledi.bg');
        foreach ($config['to'] as $email => $name) {
            $message->addTo($email, $name);
        }
        if (empty($config['replayTo'])) {
            $config['replayTo'] = $config['from'];
        }

        foreach ($config['replayTo'] as $email => $name) {
            $message->setReplyTo($email, $name);
            break;
        }
        $message->setSubject($config['subject']);

        $htmlBody = '';
        if (!empty($config['html'])) {
            $htmlBody = $config['html'];
        } elseif (!empty($config['template'])) {
            $htmlBody = file_get_contents($config['template']);
            foreach ($config['fields'] as $field => $label) {
                $htmlBody = str_replace(sprintf('{$%s}', $field), $config['post'][$field], $htmlBody);
            }
        } else {
            $htmlBody .= str_repeat('= ', $config['lineWidth'] / 2) . PHP_EOL;
            $maxWidth = 0;
            foreach ($config['fields'] as $label) {
                $currentWidth = mb_strlen($label);
                if ($currentWidth > $maxWidth) {
                    $maxWidth = $currentWidth;
                }
            }
            foreach ($config['fields'] as $field => $label) {
                $widthDiff = (strlen($label) - mb_strlen($label));
                $htmlBody .= sprintf('<strong>%s:</strong> %s', str_pad($label, $maxWidth + $widthDiff, '.', STR_PAD_RIGHT), $config['post'][$field]) . PHP_EOL;
            }
            $htmlBody .= str_repeat('= ', $config['lineWidth'] / 2);
            $htmlBody = '
			<html>
				<body>
					<table>
						<tr>
							<td>
								<div style="font-family: \'courier new\', courier, monospace; font-size: 14px;">' . nl2br($htmlBody) . '</div>
							</td>
						</tr>
					</table>
				</body>
			</html>';
        }
        $html = new MimePart($htmlBody);
        $html->type = 'text/html';
        $html->charset = 'utf-8';
        $body = new MimeMessage();
        $body->setParts([$html]);
        $message->setBody($body);   
        try {
            $transport->send($message);                        
            return true;
        } catch (RuntimeException $e) {
            return false;
        }
    }
}