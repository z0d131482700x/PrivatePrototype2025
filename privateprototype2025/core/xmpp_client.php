<?php
// privateprototype2025/core/xmpp_client.php - PRODUCTION XMPP Client
class XMPPClient {
    private $socket;
    private $jid = 'admin@xmpp.jp';
    private $password = '123456test';
    private $server = 'xmpp.jp';
    private $port = 5222;
    
    public function __construct() {
        $this->connect();
    }
    
    public function send_otp($to_jid, $otp_code) {
        $message = $this->build_otp_xml($to_jid, $otp_code);
        return $this->send_xml($message);
    }
    
    private function connect() {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        $this->socket = stream_socket_client(
            "tcp://{$this->server}:{$this->port}",
            $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context
        );
        
        if (!$this->socket) {
            throw new Exception("XMPP connection failed: $errstr");
        }
        
        $this->send_xml('<stream:stream to="' . $this->server . '" xmlns="jabber:client" xmlns:stream="http://etherx.jabber.org/streams" version="1.0">');
        $this->authenticate();
    }
    
    private function authenticate() {
        // SASL PLAIN authentication
        $auth = base64_encode("\0{$this->jid}\0{$this->password}");
        $xml = '<auth xmlns="urn:ietf:params:xml:ns:xmpp-sasl" mechanism="PLAIN">' . $auth . '</auth>';
        $this->send_xml($xml);
        
        // Wait for success
        $response = $this->read_xml();
        if (strpos($response, '<success') === false) {
            throw new Exception('XMPP authentication failed');
        }
        
        // Start new stream
        $this->send_xml('<stream:stream to="' . $this->server . '" xmlns="jabber:client" xmlns:stream="http://etherx.jabber.org/streams" version="1.0">');
    }
    
    private function build_otp_xml($to_jid, $otp) {
        return '<message to="' . htmlspecialchars($to_jid) . '" type="chat" id="otp' . time() . '">' .
               '<body>🛡️ Sovereign OTP: ' . $otp . ' (5min valid)</body>' .
               '<x xmlns="jabber:x:otp"><otp>' . $otp . '</otp><expires>' . (time() + 300) . '</expires></x>' .
               '</message>';
    }
    
    private function send_xml($xml) {
        fwrite($this->socket, $xml);
        return $this->read_xml();
    }
    
    private function read_xml() {
        return stream_get_contents($this->socket, 4096);
    }
    
    public function disconnect() {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
}
?>
