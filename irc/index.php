<?php
require '/lib/openid.php';
require("/lib/SteamCondenser.php");
$_STEAMAPI = "YOURSTEAMAPIKEY";
try 
{
    $openid = new LightOpenID('localhost:8080');
    if(!$openid->mode) 
    {
        if(isset($_GET['login'])) 
        {
            $openid->identity = 'http://steamcommunity.com/openid/?l=english';    // This is forcing english because it has a weird habit of selecting a random language otherwise
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="?login" method="post">
    <input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png">
</form>
<?php
    } 
    elseif($openid->mode == 'cancel') 
    {
        echo 'User has canceled authentication!';
    } 
    else 
    {
        if($openid->validate()) 
        {
                $id = $openid->identity;
                // identity is something like: http://steamcommunity.com/openid/id/76561197960435530
                // we only care about the unique account ID at the end of the URL.
                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $steamUser);
                echo "User is logged in (steamID: $steamUser[1])\n";
				$steamUser = new SteamId('00000000000000000');
				echo "Welcome, " . $steamUser->getNickname();
        } 
    }
} 
catch(ErrorException $e) 
{
    echo $e->getMessage();
}
?>