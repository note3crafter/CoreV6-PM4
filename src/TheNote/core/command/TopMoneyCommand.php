<?php


//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\event\Listener;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class TopMoneyCommand extends Command implements Listener
{
    private $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("topmoney", $api->getSetting("prefix") . $api->getLang("topmoneyprefix"), "/topmoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = new BaseAPI();
        $moneys = new Config(Main::getInstance()->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $moneys = $moneys->get("money", []);
        $money = [];
        foreach ($moneys as $p => $m) {
            $money[] = ["Money" => $m ,"Player" => $p];
        }
        rsort($money);
        $sender->sendMessage($api->getLang("topmoneytitle"));
        $money = array_slice($money, 0, 10);
        foreach ($money as $rank => $data) {
            $datas = round($data["Money"], 2);
            $sender->sendMessage("§6" . ($rank + 1) . "§f. " . $data["Player"] . " §f:§e " . $datas . "$");
        }
        return true;
    }
}