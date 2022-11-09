<?php 


namespace App\Service;
// Un service pour manipuler les devises

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Extension\AbstractExtension as ExtensionAbstractExtension;
use Twig\TwigFilter;
class DeviseService extends ExtensionAbstractExtension
{
    private SessionInterface $session;
    private $client;


    public function __construct(SessionInterface $session,HttpClientInterface $client)
    {
        
        $this->session = $session;
        $this->client = $client;    
        
    }

    public function getFilters()
    {
        return [
            new TwigFilter('currency_convert', [$this, 'convertPrice']),
        ];
    }
    
    public function convertPrice($sommeEuro) {
        
        $response = $this->client->request(
            'GET',
            'https://api.getgeoapi.com/v2/currency/convert?api_key=b4357ea8742e4974ea34e92bbfd395d3c4b328ff'
        );
        $deviseCourant = $this->session->get('devise');
        $tabApi = $response->toArray();
        foreach ($tabApi as $key => $value) {
            if($key == 'rates'){
                $rates = $value;
                foreach ($rates as $key => $value) {
                    if($key == $deviseCourant){
                        $tauxDevise = $value['rate'];
                    }
                }
            }
        }
        $valeurConvertie = $sommeEuro * $tauxDevise;
        return   $valeurConvertie;
    }

    public function saveDevise($devise){
        
            // $this->session->set('devise',$devise);
            // $deviseSave = $this->session->get('devise');
        
    }
}
?>