<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\services\CityService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class GeocoderApiClient extends Component
{
    public const BASE_URL = 'https://geocode-maps.yandex.ru/1.x/';

    /**
     * @param string $geocode
     * @return array
     */
    public function getCoords(string $geocode): array
    {
        $client = new Client(['base_uri' => self::BASE_URL]);

        try {
            $request = new Request('GET', '');
            $response = $client->send($request, [
                'query' => [
                    'geocode' => $geocode,
                    'apikey' => Yii::$app->params['geocoderApiKey'],
                    'format' => 'json'
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                $message = 'Response error: ' . $response->getReasonPhrase();
                throw new BadResponseException($message);
            }

            $content = $response->getBody()->getContents();
            $responseData = json_decode($content, associative: false);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ServerException('Invalid json format', $request);
            }

            $featureMembers = $responseData
                ?->response
                ?->GeoObjectCollection
                ?->featureMember;

            $result = [];

            foreach ($featureMembers ?? [] as $i => $featureMember) {
                $geoObject = $featureMember?->GeoObject;
                $geocoderMetaData = $geoObject?->metaDataProperty?->GeocoderMetaData;
                $components = $geocoderMetaData?->Address?->Components ?? [];

                $pos = !$geoObject?->Point?->pos ?: explode(' ', $geoObject->Point->pos);
                $text = $geocoderMetaData?->text;
                $city = array_values(array_filter($components, function ($city) {
                    return $city?->kind === 'locality';
                }))[0]->name ?? null;

                if (isset($pos, $text, $city)) {
                    $result[$i] = [
                        'pos' => $pos,
                        'text' => $text,
                        'city' => $city
                    ];
                }
            }
        } catch (RequestException $ex) {
            $result = [];
        }

        return $result;
    }
}
