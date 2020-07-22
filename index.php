<?php
require __DIR__ . '/vendor/autoload.php';
$bugsnag = Bugsnag\Client::make();

function warning(string $message, array $context = []) {
    // this will handle the logging towards Bugsnag
    global $bugsnag;
    $bugsnag->notifyError($context['name'] ?? 'unknown', $message);
    return $message;    
}

class BookingService
{

    private $trivagoHandler;
   

    public function __construct()
    {
        $this->trivagoHandler = new TrivagoHandler();

    }

    public function create()
    {
        $Booking = new Booking();
        $Booking->status = 'created';
        $Booking->save();

        // trigger the TrivagoHandler to send a create request to Trivago
        if($Booking->referral_provider === "Trivago" )
            return $this->trivagoHandler->create($Booking);
        return false;
    }

    public function cancel($id)
    {
        $Booking = new Booking($id);
        $Booking->status = 'cancellled';
        $Booking->save();

        // trigger the TrivagoHandler to send a cancel request to Trivago\
        if($Booking->referral_provider === "Trivago" )
            return $this->trivagoHandler->cancel($Booking);
        return false;
    }
}

class TrivagoHandler
{
    /**
     * API key for header:X-Trv-Ana-Key
     * @var string
     */
    protected $apiKey = 'lorem-ipsum';

    /**
     * Trivago Conversion API endpoint
     * @var string
     */
    protected $endpoint = 'https://secde.trivago.com/tracking/booking';

    /*post api  */

    protected $client;

    public function __construct()
    {
        $client = new \GuzzleHttp\Client(
            [
                'defaults' => [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-Trv-Ana-Key' => $this->apiKey
                    ]
                ]
            ]
        );
    }

    public function create(Booking $param){
//        if($param->referral_provider !== "Trivago" )
//            return false;
        try {
            $response = $this->client->post($this->endpoint, [
                'json' => [
                    "trv_reference" => $param->trv_reference,
                    "advertiser_id" => $param->advertiser_id,
                    "hotel" => $param->hotel,
                    "arrival" => $param->arrival,
                    "departure" => $param->departure,
                    "volume" => $param->volume,
                    "booking_id" => $param->booking_id,
                    "currency" => $param->currency,
                    "date_format" => $param->date_format,
                    "booking_date" => $param->booking_date,
                    "booking_date_format" => $param->booking_date_format,
                    "number_of_rooms" => $param->number_of_rooms
                ]
            ]);
            return true;
        } catch (\GuzzleHttp\Exception\ServerException | \GuzzleHttp\Exception\ClientException $e) {
            $resp_obj = json_decode($e->getResponse()->getBody());
            error_log(warning($resp_obj->errorMessage, ['name'=> 'create']));
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            error_log(warning($e->getMessage(), ['name'=> 'GuzzleError']));
        }
        return false;
    }

    /* delete api */
    public function cancel(Booking $param ){
        try {
            $response = $this->client->delete($this->endpoint, [
                'json' => [
                    "trv_reference" => $param->trv_reference,
                    "advertiser_id" => $param->advertiser_id,
                    "hotel" => $param->hotel,
                    "arrival" => $param->arrival,
                    "departure" => $param->departure,
                    "volume" => $param->volume,
                    "booking_id" => $param->booking_id,
                    "currency" => $param->currency,
                    "date_format" => $param->date_format,
                    "booking_date" => $param->booking_date,
                    "booking_date_format" => $param->booking_date_format,
                    "number_of_rooms" => $param->number_of_rooms,
                    "refund_amount" => $param->refund_amount,
                    "refund_ratio" => $param->refund_ratio,
                    "cancellation_date" => $param->cancellation_date,
                    "cancellation_date_format" => $param->cancellation_date_format
                ]
            ]);
            return true;

        } catch (\GuzzleHttp\Exception\ServerException | \GuzzleHttp\Exception\ClientException $e) {
            $resp_obj = json_decode($e->getResponse()->getBody());
            error_log(warning($resp_obj->errorMessage, ['name' => 'cancel']));
        }
        catch (\GuzzleHttp\Exception\GuzzleException $e) {
            error_log(warning($e->getMessage(), ['name'=> 'GuzzleError']));
        }
        return false;
    }
}



