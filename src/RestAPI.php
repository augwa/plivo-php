<?php

namespace Plivo;

use GuzzleHttp\Client;

/**
 * Class RestAPI
 * @package Plivo
 */
class RestAPI
{

    /** @var string */
    private $api;

    /** @var string */
    private $auth_id;

    /** @var string */
    private $auth_token;

    /**
     * RestAPI constructor.
     *
     * @param string $auth_id
     * @param string $auth_token
     * @param string $url
     * @param string $version
     *
     * @throws PlivoError
     */
    function __construct(
        $auth_id,
        $auth_token,
        $url = "https://api.plivo.com",
        $version = "v1"
    )
    {
        if ((!isset($auth_id)) || (!$auth_id)) {
            throw new PlivoError("no auth_id");
        }
        if ((!isset($auth_token)) || (!$auth_token)) {
            throw new PlivoError("no auth_token");
        }
        $this->version = $version;
        $this->api = $url . "/" . $this->version . "/Account/" . $auth_id;
        $this->auth_id = $auth_id;
        $this->auth_token = $auth_token;
    }

    /**
     * @param string $uri
     * @param array $post_params
     * @param string $signature
     * @param string $auth_token
     *
     * @return bool
     */
    public static function validate_signature(
        $uri,
        array $post_params = [],
        $signature,
        $auth_token
    )
    {
        ksort($post_params);
        foreach ($post_params as $key => $value) {
            $uri .= "$key$value";
        }
        $generated_signature = base64_encode(hash_hmac("sha1", $uri, $auth_token, true));

        return $generated_signature == $signature;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $params
     *
     * @return array
     */
    private function request(
        $method,
        $path,
        array $params = []
    )
    {
        $url = $this->api . rtrim($path, '/') . '/';

        $client = new Client(
            [
                'base_uri' => $url,
                'auth' => [
                    $this->auth_id,
                    $this->auth_token
                ],
                'http_errors' => false
            ]
        );

        switch (strtolower($method)) {
            case "post":
                $body = json_encode($params, JSON_FORCE_OBJECT);

                $response = $client->post(
                    '',
                    [
                        'headers' => ['Content-type' => 'application/json'],
                        'body' => $body,
                    ]
                );
                break;

            case "delete":
                $response = $client->delete(
                    '',
                    [
                        'query' => $params,
                    ]
                );
                break;

            case "get":
            default:
                $response = $client->get(
                    '',
                    [
                        'query' => $params,
                    ]
                );
                break;

        }

        $responseData = json_decode($response->getBody(), true);
        $status = $response->getStatusCode();

        return [
            "status" => $status,
            "response" => $responseData
        ];
    }

    /**
     * @param array $params
     * @param string $key
     *
     * @return string
     * @throws PlivoError
     */
    private function pop(
        array $params,
        $key
    )
    {
        $val = $params[$key];
        if (!$val) {
            throw new PlivoError($key . " parameter not found");
        }
        unset($params[$key]);

        return $val;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_account(
        array $params = []
    )
    {
        return $this->request('GET', '', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_account(
        array $params = []
    )
    {
        return $this->request('POST', '', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_subaccounts(
        array $params = []
    )
    {
        return $this->request('GET', '/Subaccount/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_subaccount(
        array $params = []
    )
    {
        return $this->request('POST', '/Subaccount/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_subaccount(
        array $params = []
    )
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('GET', '/Subaccount/' . $subauth_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_subaccount(
        array $params = []
    )
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('POST', '/Subaccount/' . $subauth_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_subaccount(
        array $params = []
    )
    {
        $subauth_id = $this->pop($params, "subauth_id");

        return $this->request('DELETE', '/Subaccount/' . $subauth_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_applications(
        array $params = []
    )
    {
        return $this->request('GET', '/Application/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_application(
        array $params = []
    )
    {
        return $this->request('POST', '/Application/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_application(
        array $params = []
    )
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('GET', '/Application/' . $app_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_application(
        array $params = []
    )
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('POST', '/Application/' . $app_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_application(
        array $params = []
    )
    {
        $app_id = $this->pop($params, "app_id");

        return $this->request('DELETE', '/Application/' . $app_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_numbers(
        array $params = []
    )
    {
        return $this->request('GET', '/Number/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function search_numbers(
        array $params = []
    )
    {
        return $this->request('GET', '/AvailableNumber/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('GET', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function rent_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/AvailableNumber/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function unrent_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('DELETE', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function search_phone_numbers(
        array $params = []
    )
    {
        return $this->request('GET', '/PhoneNumber/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function buy_phone_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/PhoneNumber/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function link_application_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function unlink_application_number(
        array $params = []
    )
    {
        $number = $this->pop($params, "number");
        $params = ["app_id" => ""];

        return $this->request('POST', '/Number/' . $number . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_number_group(
        array $params = []
    )
    {
        return $this->request('GET', '/AvailableNumberGroup/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_number_group_details(
        array $params = []
    )
    {
        $group_id = $this->pop($params, "group_id");

        return $this->request('GET', '/AvailableNumberGroup/' . $group_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function rent_from_number_group(
        array $params = []
    )
    {
        $group_id = $this->pop($params, "group_id");

        return $this->request('POST', '/AvailableNumberGroup/' . $group_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_cdrs(
        array $params = []
    )
    {
        return $this->request('GET', '/Call/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_cdr(
        array $params = []
    )
    {
        $record_id = $this->pop($params, 'record_id');

        return $this->request('GET', '/Call/' . $record_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_live_calls(
        array $params = []
    )
    {
        $params["status"] = "live";

        return $this->request('GET', '/Call/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_live_call(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');
        $params["status"] = "live";

        return $this->request('GET', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function make_call(
        array $params = []
    )
    {
        return $this->request('POST', '/Call/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_all_calls(
        array $params = []
    )
    {
        return $this->request('DELETE', '/Call/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function transfer_call(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_call(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function record(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Record/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function stop_record(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Record/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function play(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Play/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function stop_play(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Play/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function speak(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/Speak/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function stop_speak(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('DELETE', '/Call/' . $call_uuid . '/Speak/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function send_digits(
        array $params = []
    )
    {
        $call_uuid = $this->pop($params, 'call_uuid');

        return $this->request('POST', '/Call/' . $call_uuid . '/DTMF/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_request(
        array $params = []
    )
    {
        $request_uuid = $this->pop($params, 'request_uuid');

        return $this->request('DELETE', '/Request/' . $request_uuid . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_live_conferences(
        array $params = []
    )
    {
        return $this->request('GET', '/Conference/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_all_conferences(
        array $params = []
    )
    {
        return $this->request('DELETE', '/Conference/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_live_conference(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('GET', '/Conference/' . $conference_name . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_conference(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('DELETE', '/Conference/' . $conference_name . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function hangup_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('DELETE', '/Conference/' . $conference_name . '/Member/' . $member_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function play_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Play/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function stop_play_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Play/',
            $params
        );
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function speak_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Speak/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function deaf_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Deaf/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function undeaf_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Deaf/',
            $params
        );
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function mute_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Mute/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function unmute_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request(
            'DELETE',
            '/Conference/' . $conference_name . '/Member/' . $member_id . '/Mute/',
            $params
        );
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function kick_member(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);
        $member_id = $this->pop($params, 'member_id');

        return $this->request('POST', '/Conference/' . $conference_name . '/Member/' . $member_id . '/Kick/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function record_conference(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('POST', '/Conference/' . $conference_name . '/Record/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function stop_record_conference(
        array $params = []
    )
    {
        $conference_name = $this->pop($params, 'conference_name');
        $conference_name = rawurlencode($conference_name);

        return $this->request('DELETE', '/Conference/' . $conference_name . '/Record/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_recordings(
        array $params = []
    )
    {
        return $this->request('GET', '/Recording/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_recording(
        array $params = []
    )
    {
        $recording_id = $this->pop($params, 'recording_id');

        return $this->request('GET', '/Recording/' . $recording_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_recording(
        array $params = []
    )
    {
        $recording_id = $this->pop($params, 'recording_id');

        return $this->request('DELETE', '/Recording/' . $recording_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_endpoints(
        array $params = []
    )
    {
        return $this->request('GET', '/Endpoint/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_endpoint(
        array $params = []
    )
    {
        return $this->request('POST', '/Endpoint/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_endpoint(
        array $params = []
    )
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('GET', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_endpoint(
        array $params = []
    )
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('POST', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_endpoint(
        array $params = []
    )
    {
        $endpoint_id = $this->pop($params, 'endpoint_id');

        return $this->request('DELETE', '/Endpoint/' . $endpoint_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_incoming_carriers(
        array $params = []
    )
    {
        return $this->request('GET', '/IncomingCarrier/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_incoming_carrier(
        array $params = []
    )
    {
        return $this->request('POST', '/IncomingCarrier/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_incoming_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('GET', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_incoming_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('POST', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_incoming_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('DELETE', '/IncomingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_outgoing_carriers(
        array $params = []
    )
    {
        return $this->request('GET', '/OutgoingCarrier/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_outgoing_carrier(
        array $params = []
    )
    {
        return $this->request('POST', '/OutgoingCarrier/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_outgoing_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('GET', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_outgoing_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('POST', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_outgoing_carrier(
        array $params = []
    )
    {
        $carrier_id = $this->pop($params, 'carrier_id');

        return $this->request('DELETE', '/OutgoingCarrier/' . $carrier_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_outgoing_carrier_routings(
        array $params = []
    )
    {
        return $this->request('GET', '/OutgoingCarrierRouting/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function create_outgoing_carrier_routing(
        array $params = []
    )
    {
        return $this->request('POST', '/OutgoingCarrierRouting/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_outgoing_carrier_routing(
        array $params = []
    )
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('GET', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function modify_outgoing_carrier_routing(
        array $params = []
    )
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('POST', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function delete_outgoing_carrier_routing(
        array $params = []
    )
    {
        $routing_id = $this->pop($params, 'routing_id');

        return $this->request('DELETE', '/OutgoingCarrierRouting/' . $routing_id . '/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function pricing(
        array $params = []
    )
    {
        return $this->request('GET', '/Pricing/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function send_message(
        array $params = []
    )
    {
        return $this->request('POST', '/Message/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_messages(
        array $params = []
    )
    {
        return $this->request('GET', '/Message/', $params);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function get_message(
        array $params = []
    )
    {
        $record_id = $this->pop($params, 'record_id');

        return $this->request('GET', '/Message/' . $record_id . '/', $params);
    }
}