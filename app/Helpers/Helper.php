<?php


namespace App\Helpers;





use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Helper
{

     public static function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public static function get_local_time(){

        $ip = file_get_contents("http://ipecho.net/plain");

        $url = 'http://ip-api.com/json/'.$ip;

        $tz = file_get_contents($url);

        $tz = json_decode($tz,true)['timezone'];

        return $tz;

    }

    public static function createMeeting($params)
    {

        $bbb = new BigBlueButton();


        $createMeetingParams = new CreateMeetingParameters($params['meetingUrl'] , $params['meetingName']);
        $createMeetingParams->setAttendeePassword($params['attendeePassword']);
        $createMeetingParams->setModeratorPassword($params['moderatorPassword']);
        $createMeetingParams->setLogoutUrl($params['logoutUrl']);
        $files = ['https://img.youtube.com/vi/FAFlmkh0tEM/0.jpg','http://www.africau.edu/images/default/sample.pdf','https://file-examples.com/wp-content/uploads/2017/08/file_example_PPT_250kB.ppt'];

        foreach ($files as $file)
        {
            $createMeetingParams->addPresentation($file);

        }

        if (isset($params['muteAllUser']))
        {


            $createMeetingParams->setMuteOnStart($params['muteAllUser']);
            $createMeetingParams->setLockSettingsDisableMic($params['muteAllUser']);

        }
        if (isset($params['moderator_approval']))
        {


            $params['moderator_approval'] ? $createMeetingParams->setGuestPolicyAskModerator() : $createMeetingParams->setGuestPolicyAlwaysAccept();

        }
        if (isset($params['setRecord']))
        {
            $createMeetingParams->setRecord($params['setRecord']);
            $createMeetingParams->setAllowStartStopRecording($params['setRecord']);
        }
        if (isset($params['welcome_message']))
        {
            $createMeetingParams->setWelcomeMessage($params['welcome_message']);
        }


        $response = $bbb->createMeeting($createMeetingParams);


        return $response;
    }

    public static function joinMeeting($params)
    {
        $bbb = new BigBlueButton();
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['username'], $params['password']);
        $joinMeetingParams->setRedirect(true);
        $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
        return $apiUrl;
    }

    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1000);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
    public static function recordingLists($url)
    {
        $recordingList = [];
        $recordingParams = new GetRecordingsParameters();
        $recordingParams->setMeetingId($url);
        $bbb = new BigBlueButton();
        $response = $bbb->getRecordings($recordingParams);
        if ($response->getMessageKey() == null) {
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                $recordingList[] = $recording ;
            }
        }

        $roomsRecordingList = [];

        foreach ($recordingList as $recording)
        {
            if ($recording->published == 'true')
            {
                $roomsRecordingList [] = $recording;
            }
        }
        return $roomsRecordingList;
    }

}