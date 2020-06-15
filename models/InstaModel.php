<?php


namespace app\models;


use http\Exception\BadHeaderException;
use Yii;
use yii\base\Model;
use function GuzzleHttp\Psr7\try_fopen;

class InstaModel extends Model
{
    public $usernames;

    public function rules()
    {
        return [
            [['usernames'], 'required']
        ];
    }

    public function getInstPost($username)
    {
        try {
            $instSource = file_get_contents('http://instagram.com/' . $username);
            $shards = explode('window._sharedData = ', $instSource);
            $instJson = explode(';</script>', $shards[1]);
            $instArray = json_decode($instJson[0], TRUE);
            $blocked = $instArray['entry_data']['ProfilePage'][0]["graphql"]['user']["blocked_by_viewer"];
            if ($blocked) {
                return $blocked;
            } else {
                $results = $instArray['entry_data']['ProfilePage'][0]["graphql"]['user']["edge_owner_to_timeline_media"]["edges"][0];
                return $results;
            }
        } catch (\Exception $e){
            return false;
        }
    }

}