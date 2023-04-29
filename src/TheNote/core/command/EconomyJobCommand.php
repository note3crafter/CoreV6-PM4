<?php

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class EconomyJobCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("job", $api->getSetting("prefix") . $api->getLang("ecjobprefix"), "/job", ["arbeit", "arbeitsmarkt"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        $jobs = new Config($this->plugin->getDataFolder() . Main::$setup . "Jobs.yml", Config::YAML);
        $pjobs = new Config($this->plugin->getDataFolder() . Main::$cloud . "jobsplayer.yml", Config::YAML);

        switch (array_shift($args)) {
            case "join":
                    if ($pjobs->exists($sender->getName())) {
                        $sender->sendMessage($api->getSetting("error") . "§cDu bist bereits in einem Job! Bitte verlasse es erst mit §e/job leave (jobname)");
                    } else {
                        $job = array_shift($args);
                        if (trim($job) === "") {
                            $sender->sendMessage($api->getSetting("info") . "§cBenutze: /job join (jobname)");
                            break;
                        }
                        if ($jobs->exists($job)) {
                            $pjobs->set($sender->getName(), $job);
                            $pjobs->save();
                            $sender->sendMessage($api->getSetting("money") . "§dDu bist den Job §f:§e " . $job . " §r§dBeigetreten.");
                        } else {
                            $sender->sendMessage($api->getSetting("error") . "§cDieser Job existiert nicht");
                        }
                    }

                break;
            case "retire":
            case "fire":
            case "leave":
            if ($pjobs->exists($sender->getName())) {
                    $job = $pjobs->get($sender->getName());
                    $pjobs->remove($sender->getName());
                    $pjobs->save();

                    $sender->sendMessage($api->getSetting("money") . "§dDu hast deinen Job§f : §e" . $job . "§d Gekündigt!");
                } else {
                    $sender->sendMessage($api->getSetting("error") . "§cDu bist in keinem Job!");
                }
                break;
            case "list":
                $sender->sendMessage($api->getSetting("money") . "§dJobs§f : §eminer, holzfaeller");
                break;
            case "me":
            case "see":
                if ($pjobs->exists($sender->getName())) {
                    $sender->sendMessage($api->getSetting("money") . "§dDein Job ist §f:§e " . $pjobs->get($sender->getName()));
                } else {
                    $sender->sendMessage($api->getSetting("error") . "§cDu bist in keinem Job!");
                }
                break;
            default:
                $sender->sendMessage($api->getSetting("info") . "§cBenutze §f:§e /job (join, list, leave, me)");
        }
        return true;
    }
}