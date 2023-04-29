<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class OnlineTimeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("onlinetime", $api->getSetting("prefix") . "§dDeine Spielzeit", "/onlinetime", ["ot", "playtime", "otime"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        $helplist = [
            "§a-=-=§9OnlineTime§a=-=-",
            "§b/ot total (player)",
            "§b/ot session (player)",
            "§b/ot top (page)"
        ];
        $h = implode("\n", $helplist);
        if (empty($args[0])) {
            $time = explode(":", $this->plugin->getTotalTime($sender->getName()));
            $sender->sendMessage("§9Your total online time is: §b" . $time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs");
            return true;
        } elseif (isset($args[0])) {
            switch ($args[0]) {
                case "total":
                    if (!isset($args[1])) {
                        $time = explode(":", $this->plugin->getTotalTime($sender->getName()));
                        $sender->sendMessage("§9Your total online time is: §b" . $time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs");
                    } else if (isset($args[1])) {
                        // strtolower($args[1]);
                        if ($api->findPlayer($args[1]) !== null) {
                            $target = $api->findPlayer($args[1]);
                            $time = explode(":", $this->plugin->getTotalTime($target->getName()));
                            $sender->sendMessage("§9The total online time of " . $target->getName() . " is: §b" . $time[0] . " §9hrs §b" . $time[1] . "§9mins §b" . $time[2] . " §9secs");
                        } else {
                            if ($this->plugin->db->hasTime($api->findPlayer($args[1]))) {
                                $time = explode(":", $this->plugin->getTotalTime($api->findPlayer($args[1])));
                                $sender->sendMessage("§9The total online time of " . $args[1] .  " is: §b" . $time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs");
                            } else $sender->sendMessage("§cPlayer not found in database");
                        }
                    }
                    break;
                case "session":
                    if (!isset($args[1])) {
                        $time = explode(":", $this->plugin->getSessionTime($sender->getName()));
                        $sender->sendMessage("§9Your current session time is: §b" . $time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs");
                    } else if (isset($args[1])) {
                        if ($this->plugin->getServer()->getPlayerByprefix($args[1]) !== null) {
                            $name = $this->plugin->getServer()->getPlayerByPrefix($args[1])->getName();
                            $time = explode(":", $this->plugin->getSessionTime($name));
                            $sender->sendMessage("§9The current session time of $name is: §b" . $time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs");
                        } else {
                            $sender->sendMessage("§c$args[1] is not online");
                        }
                    }
                    break;
                case "top":
                    $query = "SELECT username, time FROM players ORDER BY time;";
                    $result = $this->plugin->db->getDatabase()->query($query);
                    $place = 1;
                    $data = [];
                    $start = microtime(true);
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        $data[$row["username"]] = $row["time"];
                        $place++;
                    }
                    arsort($data);

                    $i = 0;
                    $pagelength = 10;
                    $n = count($data);
                    $pages = round($n / $pagelength);
                    $page = 1;
                    if (isset($args[1]) && is_numeric($args[1])) {
                        if ($args[1] > ($n / $pagelength)) {
                            $sender->sendMessage("§cPage number is too large, max page number: $n");
                            return false;
                        }
                        $page = $args[1];
                    }
                    $sender->sendMessage("§bTop Online Times");
                    $sender->sendMessage("§6Displaying page §b" . ($page) . "§6 out of §b$pages");
                    foreach ($data as $key => $val) {
                        $i++;
                        if ($i >= $pagelength * ($page - 1) && $i <= (($pagelength * ($page - 1)) + 10)) {
                            $session = in_array($key, $this->plugin->getServer()->getOnlinePlayers()) ? Main::$times[$key] : 0;

                            $formattedtime = $this->plugin->getFormattedTime(($val + $session));
                            $sender->sendMessage("§l§9$i.  §r§a$key §b" . $formattedtime);
                        }
                    }
                    break;
                case "reset":
                    if ($sender->hasPermission("reset.onlinetime")) {
                        if (isset($args[1])) {
                            if ($args[1] == "all") {
                                unlink($this->plugin->getDataFolder() . Main::$cloud . "onlinetimes.db");
                                $sender->sendMessage("Reset All online times");
                            }
                        }
                    }
                    break;
                default:
                    $sender->sendMessage($h);
                    if ($sender->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
                        $sender->sendMessage("§b/ot reset all  §aReset All Online Time data");
                    }
                    return true;
            }
        } else {
            $sender->sendMessage($h);
            if ($sender->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
                $sender->sendMessage("§b/ot reset all  §aReset All Online Time data");
            }
        }
    }
        /*if (count($args) > 0) {
            throw new InvalidCommandSyntaxException();
        }
        $time = ((int)floor(microtime(true) * 1000)) - $sender->getFirstPlayed() ?? microtime();
        $seconds = floor($time % 60);
        $minutes = null;
        $hours = null;
        $days = null;
        if ($time >= 60) {
            $minutes = floor(($time % 3600) / 60);
            if ($time >= 3600) {
                $hours = floor(($time % (3600 * 24)) / 3600);
                if ($time >= 3600 * 24) {
                    $days = floor($time / (3600 * 24));
                }
            }
        }
        $uptime = ($minutes !== null ?
                ($hours !== null ?
                    ($days !== null ?
                        "$days tage "
                        : "") . "$hours st "
                    : "") . "$minutes min "
                : "") . "$seconds sek";
        $sender->sendMessage("§dSpielzeit:§f:§e " . $uptime);
        return false;
    }*/
}