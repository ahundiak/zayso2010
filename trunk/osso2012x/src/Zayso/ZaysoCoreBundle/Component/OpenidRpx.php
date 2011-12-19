<?php
namespace Zayso\ZaysoCoreBundle\Component;

use Symfony\Component\HttpFoundation\Request;

class OpenidRpx
{
    public function __construct(Request $request, $rpxApiKey)
    {
        $this->request = $request;
        $this->rpxApiKey = $rpxApiKey;
    }
    public function getProfile()
    {
        $token = $this->request->get('token');
        if (!$token)
        {
            return 'No social network token';
        }
        $post_data = array
        (
            'token'  => $token,
            'apiKey' => $this->rpxApiKey,
            'format' => 'json'
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $raw_json = curl_exec($curl);
        curl_close($curl);

        /* STEP 3: Parse the JSON auth_info response */
        $authInfo = json_decode($raw_json, true);

        if ($authInfo['stat'] != 'ok')
        {
            $error = $authInfo['err']['msg'];
            return $error;
        }

        return $authInfo['profile'];
    }
}
?>
