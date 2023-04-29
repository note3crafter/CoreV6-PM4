<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2023

namespace TheNote\core;

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockIdentifierFlattened as BIDFlattened;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType;
use pocketmine\block\DaylightSensor;
use pocketmine\block\Door;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\Opaque;
use pocketmine\block\Stair;
use pocketmine\block\StonePressurePlate;
use pocketmine\block\tile\Sign as TileSign;
use pocketmine\block\utils\RecordType;
use pocketmine\block\utils\SlabType;
use pocketmine\block\utils\TreeType;
use pocketmine\block\Wall;
use pocketmine\block\WoodenButton;
use pocketmine\block\WoodenPressurePlate;
use pocketmine\block\WoodenTrapdoor;
use pocketmine\color\Color;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\data\bedrock\LegacyBlockIdToStringIdMap;
use pocketmine\data\bedrock\LegacyToStringBidirectionalIdMap;
use pocketmine\entity\animation\TotemUseAnimation;
use pocketmine\entity\Entity;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\Axe;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemBlockWallOrFloor;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\Pickaxe;
use pocketmine\item\Record;
use pocketmine\item\Shovel;
use pocketmine\item\StringToItemParser;
use pocketmine\item\Sword;
use pocketmine\item\ToolTier;
use pocketmine\item\VanillaItems;
use pocketmine\lang\Translatable;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginException;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use TheNote\core\anderes\commands\adminshop;
use TheNote\core\anderes\commands\cb;
use TheNote\core\anderes\commands\ender;
use TheNote\core\anderes\commands\farmwelt;
use TheNote\core\anderes\commands\nether;
use TheNote\core\anderes\events\ExplosiveEgg;
use TheNote\core\anderes\events\FWPos;
use TheNote\core\anderes\events\LuckyBlockEvent;
use TheNote\core\anderes\konsum\Alkohol;

use TheNote\core\anderes\konsum\Doener;
use TheNote\core\anderes\konsum\Kokain;
use TheNote\core\anderes\konsum\Speed;
use TheNote\core\anderes\konsum\Vape;
use TheNote\core\anderes\konsum\Weed;
use TheNote\core\anderes\konsum\Zigarette;
use TheNote\core\anderes\LobbyBlockChangeTask;
use TheNote\core\command\FlySpeedCommand;
use TheNote\core\command\OnlineTimeCommand;
use TheNote\core\item\Compass;
use TheNote\core\listener\OnlineListener;
use TheNote\core\listener\SpawnProtectionListener;
use TheNote\core\tile\Campfire as TileCampfire;


use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\particle\DustParticle;

use pocketmine\world\sound\TotemUseSound;
use ReflectionClass;
use ReflectionMethod;
use TheNote\core\anderes\commands\CyberInfo;
use TheNote\core\anderes\emotes\bier;
use TheNote\core\anderes\emotes\Bitburger;
use TheNote\core\anderes\emotes\Corona;
use TheNote\core\anderes\emotes\Coronavirus;
use TheNote\core\anderes\emotes\Error;
use TheNote\core\anderes\emotes\Faul;
use TheNote\core\anderes\emotes\kacken;
use TheNote\core\anderes\emotes\kevin;
use TheNote\core\anderes\emotes\Popcorn;
use TheNote\core\anderes\emotes\pupsen;
use TheNote\core\anderes\emotes\stinkt;
use TheNote\core\anderes\emotes\toilett;
use TheNote\core\anderes\emotes\xD;
use TheNote\core\anderes\events\ItemframeblockEvent;


use TheNote\core\command\CompassCommand;
use TheNote\core\command\EconomyJobCommand;
use TheNote\core\command\ExtinguishCommand;
use TheNote\core\command\JumpCommand;
use TheNote\core\command\NearCommand;
use TheNote\core\command\RealnameCommand;
use TheNote\core\command\ScoreboardCommand;
use TheNote\core\command\SpeedCommand;
use TheNote\core\command\TopCommand;
use TheNote\core\command\WorldCommand;
use TheNote\core\entity\EntityManager;
use TheNote\core\events\AntiCheatEvent;
use TheNote\core\events\BlocketRecipes;
use TheNote\core\events\EconomyChest;
use TheNote\core\events\EconomyJob;
use TheNote\core\events\Eventsettings;
use TheNote\core\events\EventsListener;
use TheNote\core\events\LightningRod;
use TheNote\core\item\LodestoneCompass;
use TheNote\core\item\Spyglass;
use TheNote\core\player\CustomPlayer;
use TheNote\core\server\FFAArena;
use TheNote\core\server\generators\ender\EnderGenerator;
use TheNote\core\server\generators\nether\NetherGenerator;
use TheNote\core\server\generators\normal\NormalGenerator;
use TheNote\core\server\generators\void\VoidGenerator;
use TheNote\core\server\Music;
use TheNote\core\command\ItemIDCommand;
use TheNote\core\command\RankShopCommand;
use TheNote\core\command\SeePermsCommand;

use TheNote\core\invmenu\InvMenuHandler;

//Command
use TheNote\core\command\AFKCommand;
use TheNote\core\command\BurnCommand;
use TheNote\core\command\DayCommand;
use TheNote\core\command\DelWarpCommand;
use TheNote\core\command\GiveMoneyCommand;
use TheNote\core\command\HubCommand;
use TheNote\core\command\KickCommand;
use TheNote\core\command\ListWarpCommand;
use TheNote\core\command\MyMoneyCommand;
use TheNote\core\command\NickCommand;
use TheNote\core\command\NightCommand;
use TheNote\core\command\FeedCommand;
use TheNote\core\command\HealCommand;
use TheNote\core\command\NukeCommand;
use TheNote\core\command\PayMoneyCommand;
use TheNote\core\command\SetMoneyCommand;
use TheNote\core\command\SetWarpCommand;
use TheNote\core\command\SizeCommand;
use TheNote\core\command\SurvivalCommand;
use TheNote\core\command\KreativCommand;
use TheNote\core\command\AbenteuerCommand;
use TheNote\core\command\ChatClearCommand;
use TheNote\core\command\TopMoneyCommand;
use TheNote\core\command\TpaacceptCommand;
use TheNote\core\command\TpaCommand;
use TheNote\core\command\TpadenyCommand;
use TheNote\core\command\WarpCommand;
use TheNote\core\command\ZuschauerCommand;
use TheNote\core\command\FlyCommand;
use TheNote\core\command\VanishCommand;
use TheNote\core\command\BoosterCommand;
use TheNote\core\command\NightVisionCommand;
use TheNote\core\command\PayallCommand;
use TheNote\core\command\EnderChestCommand;
use TheNote\core\command\RepairCommand;
use TheNote\core\command\RenameCommand;
use TheNote\core\command\ClanCommand;
use TheNote\core\command\ClearCommand;
use TheNote\core\command\FriendCommand;
use TheNote\core\command\ClearlaggCommand;
use TheNote\core\command\UnnickCommand;
use TheNote\core\command\SignCommand;
use TheNote\core\command\TellCommand;
use TheNote\core\command\ReplyCommand;
use TheNote\core\command\PerkCommand;
use TheNote\core\command\PerkShopCommand;
use TheNote\core\command\PosCommand;
use TheNote\core\command\StatsCommand;
use TheNote\core\command\ServerStatsCommand;
use TheNote\core\command\KitCommand;
use TheNote\core\command\SetHomeCommand;
use TheNote\core\command\DelHomeCommand;
use TheNote\core\command\ListHomeCommand;
use TheNote\core\command\HomeCommand;
use TheNote\core\command\MyCoinsCommand;
use TheNote\core\command\PayCoinsCommand;
use TheNote\core\command\UserdataCommand;
use TheNote\core\command\HeiratenCommand;
use TheNote\core\command\TpallCommand;
use TheNote\core\command\FakeCommand;
use TheNote\core\command\GruppeCommand;
use TheNote\core\command\NoDMCommand;
use TheNote\core\command\CraftCommand;
use TheNote\core\command\ErfolgCommand;
use TheNote\core\command\KickallCommand;
use TheNote\core\command\VoteCommand;
use TheNote\core\command\GiveCoinsCommand;
use TheNote\core\command\SuperVanishCommand;
use TheNote\core\command\TreeCommand;
use TheNote\core\command\BanCommand;
use TheNote\core\command\BanIDListCommand;
use TheNote\core\command\BanListCommand;
use TheNote\core\command\UnbanCommand;
use TheNote\core\command\AdminItemsCommand;
use TheNote\core\command\SudoCommand;
use TheNote\core\command\SeeMoneyCommand;
use TheNote\core\command\TakeMoneyCommand;
use TheNote\core\command\BackCommand;
use TheNote\core\command\CreditsCommand;
use TheNote\core\command\GodModeCommand;
use TheNote\core\command\MilkCommand;
use TheNote\core\command\MusicCommand;
use TheNote\core\command\SetHubCommand;

//Server
use TheNote\core\events\RegelEvent;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\server\RegelServer;
use TheNote\core\server\Version;

//Events
use TheNote\core\events\ColorChat;
use TheNote\core\events\Particle;
use TheNote\core\events\DeathMessages;
use TheNote\core\events\AdminItemsEvents;
use TheNote\core\events\EconomySell;
use TheNote\core\events\EconomyShop;

//listener
use TheNote\core\listener\UserdataListener;
use TheNote\core\listener\HeiratsListener;
use TheNote\core\listener\BackListener;
use TheNote\core\listener\CollisionsListener;
use TheNote\core\listener\GroupListener;

//Emotes
use TheNote\core\emotes\burb;
use TheNote\core\emotes\geil;
use TheNote\core\emotes\happy;
use TheNote\core\emotes\sauer;
use TheNote\core\emotes\traurig;

//Server
use TheNote\core\server\RestartServer;
use TheNote\core\server\Stats;
use TheNote\core\server\PlotBewertung;
use TheNote\core\server\Rezept;

//Anderes
use TheNote\core\item\ItemManager;
use TheNote\core\blocks\PowerBlock;
use TheNote\core\server\LiftSystem\BlockBreakListener;
use TheNote\core\server\LiftSystem\BlockPlaceListener;
use TheNote\core\server\LiftSystem\PlayerJumpListener;
use TheNote\core\server\LiftSystem\PlayerToggleSneakListener;

//task
use TheNote\core\task\AFKTask;
use TheNote\core\task\CallbackTask;
use TheNote\core\task\MusicTask;
use TheNote\core\task\ScoreboardTask;
use TheNote\core\task\LeaderboardTask;
use TheNote\core\task\RTask;
use TheNote\core\task\PingTask;
use TheNote\core\task\SignStatsTask;
use TheNote\core\task\StopTimeTask;
use TheNote\core\tile\Tiles;
use TheNote\core\utils\ChestShopDataManager;
use TheNote\core\utils\CustomIds;

use TheNote\core\utils\Manager;
use TheNote\core\utils\OnlineSQLite;
use const pocketmine\BEDROCK_DATA_PATH;

class Main extends PluginBase implements Listener
{

    //PluginVersion
    public static string $version = "6.3.1 Stable";
    public static string $protokoll = "575";
    public static string $mcpeversion = "1.19.80";
    public static string $dateversion = "27.04.2023";
    public static string $plname = "CoreV6";
    public static string $configversion = "6.1.3";
    public static string $moduleversion = "6.1.3";

    private $default;
    private $padding;
    private $min, $max;
    private $multibyte;
    public $queue = [];
    public $anni = 1;
    public $myplot;
    public $config;
    public $economyapi;
    public $cplot;
    public $bedrockeconomy;
    public $invite = [];
    public $cooldown = [];
    public $interactCooldown = [];

    //Configs
    public static $clanfile = "Cloud/players/Clans/";
    public static $freundefile = "Cloud/players/Freunde/";
    public static $gruppefile = "Cloud/players/Gruppe/";
    public static $heifile = "Cloud/players/Heiraten/";
    public static $homefile = "Cloud/players/Homes/";
    public static $logdatafile = "Cloud/players/Logdata/";
    public static $scoreboardfile = "Cloud/players/Scoreboard/";
    public static $statsfile = "Cloud/players/Stats/";
    public static $userfile = "Cloud/players/User/";
    public static $backfile = "Cloud/players/";
    public static $cloud = "Cloud/";
    public static $setup = "Setup/";
    public static $lang = "Language/";

    //Anderes
    public static $instance;
    public static $restart;
    public $players = [];
    public $bank;
    public $win = null;
    public $price = null;
    public $economy;
    private $lastSent;
    public $lists = [];
    public static $godmod = [];
    public $sellSign;
    public $shopSign;
    public static $afksesion = [];
    public const INV_MENU_TYPE_WORKBENCH = "portablecrafting:workbench";

    public static $times = [];
    public OnlineSQLite $db;
    // How long to wait in seconds before not counting a players online time
    public int $timeout = 300;
    public static $lastmoved = [];

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function getMain(): self
    {
        return self::$instance;
    }

    public function onLoad(): void
    {
        self::$instance = $this;
        $start = !isset(Main::$instance);
        Main::$instance = $this;

        if ($start) {
            $generators = [
                "ender" => EnderGenerator::class,
                "void" => VoidGenerator::class,
                "nether" => NetherGenerator::class,
                "normal" => NormalGenerator::class
            ];

            foreach ($generators as $name => $class) {
                GeneratorManager::getInstance()->addGenerator($class, $name, fn() => null, true);
            }
        }
        $this->getServer()->getCraftingManager()->registerShapedRecipe(new ShapedRecipe( #Spyglass
                [
                    'AAA',
                    'CBC',
                    'CDC'
                ],
                ['A' => ItemFactory::getInstance()->get(ItemIds::STICK), 'B' => ItemFactory::getInstance()->get(ItemIds::GOLD_INGOT), 'C' => ItemFactory::getInstance()->get(ItemIds::COBBLESTONE), 'D' => ItemFactory::getInstance()->get(ItemIds::IRON_INGOT)],
                [ItemFactory::getInstance()->get(ItemIds::BELL)])
        );

        if (!$this->isSpoon()) {
            @mkdir($this->getDataFolder() . "Setup");
            @mkdir($this->getDataFolder() . "Cloud");
            @mkdir($this->getDataFolder() . "Language");
            @mkdir($this->getDataFolder() . "Cloud/players/");
            @mkdir($this->getDataFolder() . "Cloud/players/User/");
            @mkdir($this->getDataFolder() . "Cloud/players/Logdata/");
            @mkdir($this->getDataFolder() . "Cloud/players/Gruppe/");
            @mkdir($this->getDataFolder() . "Cloud/players/Heiraten/");
            @mkdir($this->getDataFolder() . "Cloud/players/Freunde/");
            @mkdir($this->getDataFolder() . "Cloud/players/Clans");
            @mkdir($this->getDataFolder() . "Cloud/players/Homes");
            @mkdir($this->getDataFolder() . "Cloud/players/Stats");
            @mkdir($this->getDataFolder() . "Cloud/players/Scoreboard");

            $this->saveResource("liesmich.txt", true);
            $this->saveResource("Setup/settings.json");
            $this->saveResource("Setup/discordsettings.yml");
            $this->saveResource("Setup/Config.yml");
            $this->saveResource("Setup/PerkSettings.yml");
            $this->saveResource("Setup/starterkit.yml");
            $this->saveResource("Setup/Modules.yml");
            $this->saveResource("Setup/Jobs.yml");
            $this->saveResource("Setup/kitsettings.yml");
            $this->saveResource("Setup/Scoreboard.yml");
            $this->saveResource("Setup/Leaderboard.yml");
            $this->saveResource("Setup/LuckyBlock.yml");

            $this->saveResource("permissions.md");
            $this->saveResource("Language/LangConfig.yml");
            $this->saveResource("Language/Lang_deu.json");
            $this->groupsgenerate();
            self::$instance = $this;
            if (isset($c["API-Key"])) {
                if (trim($c["API-Key"]) != "") {
                    if (!is_dir($this->getDataFolder() . "Setup/")) {
                        mkdir($this->getDataFolder() . "Setup/");
                    }
                    file_put_contents($this->getDataFolder() . "Setup/minecraftpocket-servers.com.vrc", "{\"website\":\"http://minecraftpocket-servers.com/\",\"check\":\"http://minecraftpocket-servers.com/api-vrc/?object=votes&element=claim&key=" . $c["API-Key"] . "&username={USERNAME}\",\"claim\":\"http://minecraftpocket-servers.com/api-vrc/?action=post&object=votes&element=claim&key=" . $c["API-Key"] . "&username={USERNAME}\"}");
                }
            }

        }
    }

    /**
     * @throws \JsonException
     */
    public function onEnable(): void
    {
        if (!$this->isSpoon()) {
            /*if (!InvMenuHandler::isRegistered()) {
                InvMenuHandler::register($this);
            }*/
            foreach (scandir($this->getServer()->getDataPath() . "worlds") as $file) {
                if (Server::getInstance()->getWorldManager()->isWorldGenerated($file)) {
                    $this->getServer()->getWorldManager()->loadWorld($file);

                }
            }
            $this->default = "";
            $this->reload();
            if (strlen($this->default) > 1) {
                $this->getLogger()->warning("The \"normal\" property in config.yml has an error - the value is too long! Assuming as \"_\".");
                $this->default = "_";
            }
            $this->padding = "";
            $this->min = 3;
            $this->max = 16;
            if ($this->max === -1 or $this->max === "-1") {
                $this->max = PHP_INT_MAX;
            }
            $this->multibyte = function_exists("mb_substr") and function_exists("mb_strlen");

            self::$instance = $this;
            $api = new BaseAPI();
            $modules = new Config($this->getDataFolder() . Main::$setup . "Modules.yml", Config::YAML);

            if ($api->getSetting("Config") == null) {
                $this->saveResource("Setup/settings.json", true);
                $this->getLogger()->info("§cDa die Settings.json fehlerhaft gespeichert wurde wurde sie ersetzt! ");
            }
            if ($api->getConfig("ConfigVersion") == Main::$configversion) {
                $this->getLogger()->info("");
            } else {
                $this->getLogger()->info("Die Config.yml ist veraltet! Daher wurde eine neue erstellt und die alte zu : ConfigOLD geändert!");
                rename($this->getDataFolder() . Main::$setup . "Config.yml", $this->getDataFolder() . Main::$setup . "ConfigOLD.yml");
                $this->saveResource("Setup/Config.yml", true);
            }
            if ($modules->get("ModulesVersion") == Main::$moduleversion) {
                $this->getLogger()->info("");
            } else {
                $this->getLogger()->info("Die Modules.yml ist veraltet! Daher wurde eine neue erstellt und die alte zu : ModulesOLD geändert!");
                rename($this->getDataFolder() . Main::$setup . "Modules.yml", $this->getDataFolder() . Main::$setup . "Modules.yml");
                $this->saveResource("Setup/Modules.yml", true);
            }
            #ShopSystem
            $this->sellSign = new Config($this->getDataFolder() . Main::$lang . "SellSign.yml", Config::YAML, array(
                "sell" => array(
                    "§f[§cVerkaufen§f]",
                    "§ePreis §f: {cost}§e$",
                    "§eMenge §f: §e{amount}",
                    "§e {item}"
                )
            ));
            $this->sellSign->save();
            $this->shopSign = new Config($this->getDataFolder() . Main::$lang . "ShopSign.yml", Config::YAML, array(
                "shop" => array(
                    "§f[§aKaufen§f]",
                    "§ePreis §f: {price}§e$",
                    "§eMenge §f: §e{amount}",
                    "§e {item}"
                )
            ));
            $this->shopSign->save();
            #ShopSystemEnde
            $this->myplot = $this->getServer()->getPluginManager()->getPlugin("MyPlot");
            $this->economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $this->multiworld = $this->getServer()->getPluginManager()->getPlugin("MultiWorld");
            $this->starterkit = $this->getServer()->getPluginManager()->getPlugin("StarterKit");
            $this->bedrockeconomy = $this->getServer()->getPluginManager()->getPlugin("BedrockEconomy");
            $this->world = $this->getServer()->getPluginManager()->getPlugin("Worlds");
            $this->cplot = $this->getServer()->getPluginManager()->getPlugin("CPlot");

            $this->config = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
            $serverstats = new Config($this->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("aktiviert", $serverstats->get("aktivieret") + 1);
            $serverstats->save();
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            $this->getServer()->getNetwork()->setName($api->getConfig("networkname"));
            $this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $this->getLogger()->info($api->getSetting("prefix") . "§6Wird Geladen...");

            //Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("clear"));
            Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("version"));


            $this->config = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
            $this->getLogger()->info($api->getSetting("prefix") . "§6Plugins wurden Erfolgreich geladen!");
            $this->bank = new Config($this->getDataFolder() . "bank.json", Config::JSON);
            $votes = new Config($this->getDataFolder() . Main::$setup . "vote.yml", Config::YAML);

            //Commands
            if ($modules->get("GamemodeCommands") === true) {
                $this->getServer()->getCommandMap()->register("gma", new AbenteuerCommand($this));
                $this->getServer()->getCommandMap()->register("gmc", new KreativCommand($this));
                $this->getServer()->getCommandMap()->register("gms", new SurvivalCommand($this));
                $this->getServer()->getCommandMap()->register("gmspc", new ZuschauerCommand($this));
            }
            if ($modules->get("ClanSystem") === true) {
                $this->getServer()->getCommandMap()->register("clan", new ClanCommand($this));
            }
            if ($modules->get("HeiratsSystem") === true) {
                $this->getServer()->getCommandMap()->register("heiraten", new HeiratenCommand($this));
                //$this->getServer()->getPluginManager()->registerEvents(new HeiratsListener($this), $this);
            }
            if ($modules->get("FriendSystem") === true) {
                $this->getServer()->getCommandMap()->register("friend", new FriendCommand($this));
            }
            if ($modules->get("Essentials") === true) {
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("kick"));
                $this->getServer()->getCommandMap()->register("afk", new AFKCommand($this));
                $this->getServer()->getCommandMap()->register("back", new BackCommand($this));
                $this->getServer()->getCommandMap()->register("burn", new BurnCommand($this));
                $this->getServer()->getCommandMap()->register("clear", new ClearCommand($this));
                $this->getServer()->getCommandMap()->register("day", new DayCommand($this));
                $this->getServer()->getCommandMap()->register("heal", new HealCommand($this));
                $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this));
                $this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));
                $this->getServer()->getCommandMap()->register("godmode", new GodModeCommand($this));
                $this->getServer()->getCommandMap()->register("id", new ItemIDCommand($this));
                $this->getServer()->getCommandMap()->register("kick", new KickCommand($this));
                $this->getServer()->getCommandMap()->register("kickall", new KickallCommand($this));
                $this->getServer()->getCommandMap()->register("milk", new MilkCommand($this));
                $this->getServer()->getCommandMap()->register("nick", new NickCommand($this));
                $this->getServer()->getCommandMap()->register("night", new NightCommand($this));
                $this->getServer()->getCommandMap()->register("nuke", new NukeCommand($this));
                $this->getServer()->getCommandMap()->register("position", new PosCommand($this));
                $this->getServer()->getCommandMap()->register("repair", new RepairCommand($this));
                $this->getServer()->getCommandMap()->register("rename", new RenameCommand($this));
                $this->getServer()->getCommandMap()->register("sign", new SignCommand($this));
                $this->getServer()->getCommandMap()->register("size", new SizeCommand($this));
                $this->getServer()->getCommandMap()->register("sudo", new SudoCommand($this));
                $this->getServer()->getCommandMap()->register("top", new TopCommand($this));
                $this->getServer()->getCommandMap()->register("tpall", new TpallCommand($this));
                $this->getServer()->getCommandMap()->register("tree", new TreeCommand($this));
                $this->getServer()->getCommandMap()->register("unnick", new UnnickCommand($this));
                $this->getServer()->getCommandMap()->register("vanish", new VanishCommand($this));
                $this->getServer()->getCommandMap()->register("extinguish", new ExtinguishCommand($this));
                $this->getServer()->getCommandMap()->register("compass", new CompassCommand($this));
                $this->getServer()->getCommandMap()->register("jump", new JumpCommand($this));
                $this->getServer()->getCommandMap()->register("realname", new RealnameCommand($this));
                $this->getServer()->getCommandMap()->register("near", new NearCommand($this));
                $this->getServer()->getCommandMap()->register("speed", new SpeedCommand($this));

            }
            if ($modules->get("GruppenSystem") === true) {
                $this->getServer()->getPluginManager()->registerEvents(new GroupListener($this), $this);
                $this->getServer()->getCommandMap()->register("group", new GruppeCommand($this));
                $this->getServer()->getCommandMap()->register("seeperms", new SeePermsCommand($this));

            }

            if ($modules->get("HomeSystem") === true) {
                $this->getServer()->getCommandMap()->register("home", new HomeCommand($this));
                $this->getServer()->getCommandMap()->register("sethome", new SetHomeCommand($this));
                $this->getServer()->getCommandMap()->register("delhome", new DelHomeCommand($this));
                $this->getServer()->getCommandMap()->register("listhome", new ListHomeCommand($this));
            }
            if ($modules->get("WarpSystem") === true) {
                $this->getServer()->getCommandMap()->register("warp", new WarpCommand($this));
                $this->getServer()->getCommandMap()->register("setwarp", new SetWarpCommand($this));
                $this->getServer()->getCommandMap()->register("delwarp", new DelWarpCommand($this));
                $this->getServer()->getCommandMap()->register("listwarp", new ListWarpCommand($this));
            }
            if ($modules->get("PerkSystem") === true) {
                $this->getServer()->getCommandMap()->register("perkshop", new PerkShopCommand($this));
                $this->getServer()->getCommandMap()->register("perk", new PerkCommand($this));

            }
            if ($modules->get("TPASystem") === true) {
                $this->getServer()->getCommandMap()->register("tpa", new TpaCommand($this));
                $this->getServer()->getCommandMap()->register("tpaccept", new TpaacceptCommand($this));
                $this->getServer()->getCommandMap()->register("tpadeny", new TpadenyCommand($this));
            }
            if ($modules->get("MSGSystem") === true) {
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("tell"));
                $this->getServer()->getCommandMap()->register("notell", new NoDMCommand($this));
                $this->getServer()->getCommandMap()->register("tell", new TellCommand($this));
                $this->getServer()->getCommandMap()->register("reply", new ReplyCommand($this));
            }
            if ($modules->get("StatsSystem") === true) {
                $this->getServer()->getCommandMap()->register("stats", new StatsCommand($this));
                $this->getServer()->getCommandMap()->register("serverstats", new ServerStatsCommand($this));
                $this->getServer()->getPluginManager()->registerEvents(new Stats($this), $this);
            }
            if ($modules->get("CoinSystem") === true) {
                $this->getServer()->getCommandMap()->register("givecoins", new GiveCoinsCommand($this));
                $this->getServer()->getCommandMap()->register("mycoins", new MyCoinsCommand($this));
                $this->getServer()->getCommandMap()->register("paycoins", new PayCoinsCommand($this));
            }


            $this->getServer()->getCommandMap()->register("adminitem", new AdminItemsCommand($this));
            $this->getServer()->getCommandMap()->register("chatclear", new ChatClearCommand($this));
            $this->getServer()->getCommandMap()->register("clearlagg", new ClearlaggCommand($this));
            //$this->getServer()->getCommandMap()->register("craft", new CraftCommand($this));
            //$this->getServer()->getCommandMap()->register("ec", new EnderChestCommand($this));
            $this->getServer()->getCommandMap()->register("erfolg", new ErfolgCommand($this));
            $this->getServer()->getCommandMap()->register("fake", new FakeCommand($this));

            $this->getServer()->getCommandMap()->register("nightvision", new NightVisionCommand($this));
            $this->getServer()->getCommandMap()->register("payall", new PayallCommand($this));
            $this->getServer()->getCommandMap()->register("supervanish", new SuperVanishCommand($this));
            $this->getServer()->getCommandMap()->register("userdata", new UserdataCommand($this));

            $this->getServer()->getCommandMap()->register("sethub", new SetHubCommand($this));
            $this->getServer()->getCommandMap()->register("hub", new HubCommand($this));
            //$this->getServer()->getCommandMap()->register("enderinvsee", new EnderInvSeeCommand($this));
            //$this->getServer()->getCommandMap()->register("invsee", new InvSeeCommand($this));
            //$this->getServer()->getCommandMap()->register("head", new HeadCommand($this));
            $this->getServer()->getCommandMap()->register("credits", new CreditsCommand($this));
            $this->getServer()->getCommandMap()->register("music", new MusicCommand($this));
            $this->getServer()->getCommandMap()->register("sb", new ScoreboardCommand($this));
            //$this->getServer()->getCommandMap()->register("onlinetime", new OnlineTimeCommand($this));
            //$this->getServer()->getPluginManager()->registerEvents(new OnlineListener($this), $this);
            //$this->db = new OnlineSQLite($this);

            $this->getServer()->getPluginManager()->registerEvents(new EconomySell($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new EconomyShop($this), $this);
            if ($modules->get("AntiCheat") === true) {
                $this->getServer()->getPluginManager()->registerEvents(new AntiCheatEvent($this), $this);
            }

            if ($api->getConfig("PowerBlock") === true) {
                $this->getServer()->getPluginManager()->registerEvents(new PowerBlock($this), $this);
            }
            if ($api->getConfig("BoosterCommand") === true) {
                $this->getServer()->getCommandMap()->register("booster", new BoosterCommand($this));
            }
            if ($api->getConfig("Kits") === true) {
                $this->getServer()->getCommandMap()->register("kit", new KitCommand($this));
            }
            if ($api->getConfig("VoteSystem") === true) {
                $this->getServer()->getCommandMap()->register("vote", new VoteCommand($this));
            } elseif ($votes->get("votes") === false) {
                $this->getLogger()->info("Voten ist Deaktiviert! Wenn du es Nutzen möchtest Aktiviere es in den Einstelungen..");
            }
            if ($modules->get("BanSystem") === true) {
                $this->getServer()->getCommandMap()->register("unban", new UnbanCommand($this));
                $this->getServer()->getCommandMap()->register("ban", new BanCommand($this));
                $this->getServer()->getCommandMap()->register("banids", new BanIDListCommand($this));
                $this->getServer()->getCommandMap()->register("banlist", new BanListCommand($this));
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("ban"));
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("unban"));
                Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("banlist"));
            }
            if ($api->getConfig("RankShopCommand") === true) {
                $this->getServer()->getCommandMap()->register("rankshop", new RankShopCommand($this));
            }
            if ($modules->get("EconomySystem") === true) {
                if ($this->economyapi === null /*or $this->bedrockeconomy === null*/) {
                    $this->getServer()->getCommandMap()->register("mymoney", new MyMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("pay", new PayMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("seemoney", new SeeMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("setmoney", new SetMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("takemoney", new TakeMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("givemoney", new GiveMoneyCommand($this));
                    $this->getServer()->getCommandMap()->register("topmoney", new TopMoneyCommand($this));
                } else {
                    $this->getLogger()->info("EconomyAPI ist nicht installiert daher wird das Interne Economysystem genutzt");
                }
            }
            $this->getServer()->getPluginManager()->registerEvents(new EconomyChest($this, new ChestShopDataManager($this->getDataFolder() . Main::$cloud . 'ChestShop.sqlite3')), $this);

            if ($this->multiworld or $this->world === null) {
                /*foreach (scandir($this->getServer()->getDataPath() . "worlds") as $file) {
                    if (Server::getInstance()->getWorldManager()->isWorldGenerated($file)) {
                        $this->getServer()->getWorldManager()->loadWorld($file);
                    }
                }*/
                $this->getServer()->getCommandMap()->register("world", new WorldCommand($this));
            } else {
                $this->getLogger()->info("Da MultiWorld oder Worlds bereits Installiert wurde ist das Interne WorldSystem Deaktiviert");
            }

            //LiftSystem
            if ($modules->get("LiftSystem") === true) {
                $this->getServer()->getPluginManager()->registerEvents(new BlockBreakListener($this), $this);
                $this->getServer()->getPluginManager()->registerEvents(new BlockPlaceListener($this), $this);
                /*if ($this->myplot === null) {
                    $this->getServer()->getPluginManager()->registerEvents(new PlayerInteractListener($this), $this);
                }*/
                $this->getServer()->getPluginManager()->registerEvents(new PlayerJumpListener($this), $this);
                $this->getServer()->getPluginManager()->registerEvents(new PlayerToggleSneakListener($this), $this);
            }
            //Emotes
            if ($modules->get("Emotes") === true) {
                $this->getServer()->getCommandMap()->register("burb", new burb($this));
                $this->getServer()->getCommandMap()->register("geil", new geil($this));
                $this->getServer()->getCommandMap()->register("happy", new happy($this));
                $this->getServer()->getCommandMap()->register("sauer", new sauer($this));
                $this->getServer()->getCommandMap()->register("traurig", new traurig($this));

            }

            //Events
            //$this->getServer()->getPluginManager()->registerEvents(new BanEventListener($this), $this); #spieler muss gekickt werden was noch nicht klappt
            $this->getServer()->getPluginManager()->registerEvents(new ColorChat($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new DeathMessages($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new Particle($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new FFAArena($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new AdminItemsEvents($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new Manager($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new EventsListener(), $this);
            $this->getServer()->getPluginManager()->registerEvents(new Eventsettings($this), $this);
            //$this->getServer()->getPluginManager()->registerEvents(new BlocketRecipes($this), $this);
            if ($api->getConfig("Lightningrod") === true) {
                $this->getServer()->getPluginManager()->registerEvents(new LightningRod($this), $this);
            }

            //listener
            $this->getServer()->getPluginManager()->registerEvents(new CollisionsListener($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new UserdataListener($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new BackListener($this), $this);

            //Server
            //$this->getServer()->getPluginManager()->registerEvents(new PlotBewertung($this), $this);
            $this->getServer()->getPluginManager()->registerEvents(new Rezept($this), $this);
            if ($modules->get("RegelSystem") === true) {
                $this->getServer()->getCommandMap()->register("regeln", new RegelServer($this));
                $this->getServer()->getPluginManager()->registerEvents(new RegelEvent($this), $this);
            }
            $this->getServer()->getCommandMap()->register("version", new Version($this));

            //task
            if ($api->getConfig("timestop") === true) {
                $this->getScheduler()->scheduleRepeatingTask(new StopTimeTask($this), 60);
            }
            $this->getScheduler()->scheduleRepeatingTask(new LeaderboardTask($this), 60);
            $this->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "particle"]), 10);

            if ($api->getConfig("Buchstabenraetsel") === true) {
                $this->getScheduler()->scheduleDelayedTask(new RTask($this), (20 * 60 * 10));
            }
            $this->getScheduler()->scheduleRepeatingTask(new PingTask($this), 20);
            $this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 60);

            //$this->getServer()->getPluginManager()->registerEvents(new SignStatsListner($this), $this);
            //$this->getScheduler()->scheduleRepeatingTask(new SignStatsTask($this), 20);


            $this->getLogger()->info($api->getSetting("prefix") . "§6Die Commands wurden Erfolgreich Regestriert");
            $this->getLogger()->info($api->getSetting("prefix") . "§6Die Core ist nun Einsatzbereit!");
            $this->Banner();
            //Discord
            $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
            $stats = new Config($this->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            if ($dcsettings->get("DC") === true) {
                if ($stats->get("serverregister") === null) {
                    $ip = $this->getServer()->getIp();
                    $port = $this->getServer()->getPort();
                    $this->sendMessage($dcsettings->get("chatprefix"), "Ein neuer Server nutzt die CoreV6 " . Main::$version . " auf $ip : $port");
                    $stats->set("serverregister", true);
                    $stats->save();
                } else {
                    $this->sendMessage($dcsettings->get("chatprefix"), $dcsettings->get("Enable"));
                }
            }
        }
        /*$cfgs = $this->getConfig()->getAll();
        if(count($cfgs) !== 1 || !isset($cfgs['radius'])){
            throw new PluginException("Invalid configuration file: Must only contain 'radius'");
        }
        if(!is_int($cfgs['radius']) || $cfgs['radius'] <= 0){
            throw new PluginException("Invalid configuration file: Radius must be a number bigger than 0");
        }

        $this->getServer()->getPluginManager()->registerEvents(
            new SpawnProtectionListener($cfgs['radius']),
            $this
        );*/
    }

    public function onDisable(): void
    {
        $api = new BaseAPI();
        if ($api->getConfig("Rejoin") === true) {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                $player->transfer($api->getConfig("IP"), $api->getConfig("Port"));
            }
        }
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        if ($dcsettings->get("DC") === true) {
            $this->sendMessage($dcsettings->get("chatprefix"), $dcsettings->get("Disable"));
        }
        $pjobs = new Config($this->getDataFolder() . Main::$cloud . "jobsplayer.yml", Config::YAML);
        $pjobs->save();
       /* foreach (self::$times as $player => $time) {
            $player = "$player";
            $player = strtolower($player);
            if(isset(self::$lastmoved[$player])){
                $diff = time() - self::$lastmoved[$player];
                if(time() - self::$lastmoved[$player] >= $this->timeout){
                    self::$times[$player] = self::$times[$player] + $diff;
                }
                unset(self::$lastmoved[$player]);
            }
            if ($this->getServer()->getPlayerByPrefix($player) !== null) {
                $p = $this->getServer()->getPlayerByPrefix($player);
            } else $p = $player;
            $old = $this->db->getRawTime($p);
            $this->db->setRawTime($p, ($old + (time() - self::$times[$player])));
            unset(self::$times[$player]);

        }*/
    }

    public function isSpoon(): bool
    {
        if (!$this->getDescription()->getVersion() == Main::$version || $this->getDescription()->getName() !== "CoreV6") {
            $this->getLogger()->error("Du benutzt keine Originale Version der Core!");
            return false;
        }
        return false;
    }

    private function Banner()
    {
        $banner = strval(
            "\n" .
            "╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗\n" .
            "╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝\n" .
            "  ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗ \n" .
            "  ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝ \n" .
            "  ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗\n" .
            "  ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝\n" .
            "Copyright by TheNote! Als eigenes ausgeben Verboten!\n" .
            "                      2017-2023                       "
        );
        $this->getLogger()->info($banner);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        //Allgemeines
        $player = $event->getPlayer();
        $fj = date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin");

        //Configs
        $modules = new Config($this->getDataFolder() . Main::$setup . "Modules.yml", Config::YAML);
        $gruppe = new Config($this->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $log = new Config($this->getDataFolder() . Main::$logdatafile . $player->getName() . ".json", Config::JSON);
        $stats = new Config($this->getDataFolder() . Main::$statsfile . $player->getName() . ".json", Config::JSON);
        $user = new Config($this->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $sstats = new Config($this->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $cfg = new Config($this->getDataFolder() . Main::$setup . "starterkit.yml", Config::YAML, array());
        $hei = new Config($this->getDataFolder() . Main::$heifile . $player->getName() . ".json", Config::JSON);
        $groups = new Config($this->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $playersb = new Config($this->getDataFolder() . Main::$scoreboardfile . $player->getName() . ".json", Config::JSON);
        $api = new BaseAPI();
        //Discord
        if ($dcsettings->get("DC") === true) {
            $all = $this->getServer()->getOnlinePlayers();
            $playername = $event->getPlayer()->getName();
            $prefix = $playerdata->getNested($player->getName() . ".group");
            $slots = $api->getSetting("slots");
            $chatprefix = $dcsettings->get("chatprefix");
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Joinmsg"));
            $stp2 = str_replace("{count}", count($all), $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $format = str_replace("{gruppe}", $prefix, $stp3);
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($format, $msg);
        }

        //Weiteres
        $log->set("Name", $player->getName());
        //$log->set("last-IP", $player->getAdress());
        $log->set("last-XboxID", $player->getPlayerInfo()->getXuid());
        $log->set("last-online", $fj);
        if ($modules->get("HeiratsSystem") === true) {
            if ($api->getMarry($player->getName(), "marry") === false) {
                $player->sendMessage($api->getSetting("heirat") . "Du bist nicht verheiratet!");
            }
        }
        if ($modules->get("ClanSystem") === true) {
            if ($gruppe->get("ClanStatus") === false) {
                $player->sendMessage($api->getSetting("clans") . "Du bist im keinem Clan!");
            }
        }
        if ($modules->get("MSGSystem") === true) {
            if ($user->get("nodm") === true) {
                $player->sendMessage($api->getSetting("info") . "Du hast deine Privatnachrrichten deaktiviert!");
            }
        }
        $this->TotemEffect($player);
        //$this->addStrike($player);
        if ($hei->get("registermarry") === false or null) {
            $form = new SimpleForm(function (Player $player, int $data = null) {
                $api = new BaseAPI();
                $result = $data;
                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        $api->addMarry($player, "partner", "Kein Partner/in");
                        $api->addMarry($player, "application", "Keine Anfrage");
                        $api->addMarry($player, "status", "Single");
                        $api->addMarry($player, "hits", 0);
                        $api->addMarry($player, "divorces", 0);
                        $api->addMarry($player, "marrypoints", 0);
                        $api->addMarry($player, "denieds", 0);
                        $api->addMarry($player, "marry", false);
                        $api->addMarry($player, "marryapplication", false);
                        $api->addMarry($player, "registermarry", true);
                        break;
                }
            });
            $form->setTitle("§e>>>§f[§eHeiraten§f]<<<");
            $form->setContent("§dHei hooo! Da es mit dem Heiratsystem zu unerklärlichen Komflikten kam habe ich mich beschlossen das System neu zu schreiben. Dazu mussten wir leider die alten Daten vom Heiratsystem komplett Löschen! Da das neue System nicht mit dem alten Kompatibel ist! Wir wünschen euch viel Spaß!");
            $form->addButton("§0Alles Klar!");
            $form->sendToPlayer($player);
        }
        //Spieler Erster Join
        if ($user->get("register") == null or false) {

            //StarterKit
            $player = $event->getPlayer();
            $ainv = $player->getArmorInventory();
            if ($api->getConfig("StarterKit") === true) {
                if ($cfg->get("Inventory", false)) {
                    foreach ($cfg->get("Slots", []) as $item) {
                        $result = ItemFactory::getInstance()->get($item["id"], $item["damage"], $item["count"]);
                        $result->setCustomName($item["name"]);
                        $result->setLore([$item["lore"]]);
                        $player->getInventory()->setItem($item["slot"], $result);
                    }
                }
                if ($cfg->get("Armor", false)) {
                    $data = $cfg->get("helm");
                    $item = ItemFactory::getInstance()->get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setHelmet($item);

                    $data = $cfg->get("chest");
                    $item = ItemFactory::getInstance()->get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setChestplate($item);

                    $data = $cfg->get("leggins");
                    $item = ItemFactory::getInstance()->get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setLeggings($item);

                    $data = $cfg->get("boots");
                    $item = ItemFactory::getInstance()->get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setBoots($item);
                }
            }
            //Groupsystem
            $defaultgroup = $groups->get("DefaultGroup");
            $player = $event->getPlayer();
            $name = $player->getName();
            if (!$playerdata->exists($name)) {
                $groupprefix = $groups->getNested("Groups." . $defaultgroup . ".groupprefix");
                $groupdisplay = $groups->getNested("Groups." . $defaultgroup . ".displayname");
                $playerdata->setNested($name . ".groupprefix", $groupprefix);
                $playerdata->setNested($name . ".group", $defaultgroup);
                $playerdata->setNested($name . ",displayname" , $groupdisplay);
                $perms = $playerdata->getNested("{$name}.permissions", []);
                $perms[] = "CoreV6";
                $playerdata->setNested("{$name}.permissions", $perms);
                $playerdata->save();
            }
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.groupprefix"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);

            //Group Perms
            $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
            foreach ($permissionlist as $name => $data) {
                $player->addAttachment($this)->setPermission($data, true);
            }

            //Economy
            $amount = $api->getConfig("DefaultMoney");
            if ($api->getMoney($player) == null) {
                $api->setMoney($player, $amount);
            }
            $api->setCoins($player, $api->getConfig("DefaultCoins"));
            //Register
            $sstats->set("Users", $sstats->get("Users") + 1);
            $sstats->save();
            $log->set("first-join", $fj);
            $log->set("first-ip", $player->getNetworkSession()->getIp());
            $log->set("first-XboxID", $player->getXuid());
            $log->set("first-uuid", $player->getUniqueId());
            $log->save();
            $gruppe->set("Nick", false);
            $gruppe->set("NickPlayer", false);
            $gruppe->set("Nickname", $player->getName());
            $gruppe->set("ClanStatus", false);
            $gruppe->set("Clan", "No Clan");
            $gruppe->save();
            $user->set("Clananfrage", false);
            $user->set("Clan", "No Clan");
            $user->set("register", true);
            $api->addMarry($player, "partner", "Kein Partner/in");
            $api->addMarry($player, "application", "Keine Anfrage");
            $api->addMarry($player, "status", "Single");
            $api->addMarry($player, "hits", 0);
            $api->addMarry($player, "divorces", 0);
            $api->addMarry($player, "marrypoints", 0);
            $api->addMarry($player, "denieds", 0);
            $api->addMarry($player, "marry", false);
            $api->addMarry($player, "marryapplication", false);
            $user->set("nodm", false);
            $user->set("rulesaccpet", false);
            $user->set("clananfrage", false);
            $user->set("heistatus", false);
            $user->set("accept", false);
            $user->set("starterkit", true);
            $user->set("explode", false);
            $user->set("angry", false);
            $user->set("redstone", false);
            $user->set("smoke", false);
            $user->set("lava", false);
            $user->set("heart", false);
            $user->set("flame", false);
            $user->set("portal", false);
            $user->set("spore", false);
            $user->set("splash", false);
            $user->set("explodeperkpermission", false);
            $user->set("angryperkpermission", false);
            $user->set("redstoneperkpermission", false);
            $user->set("smokeperkpermission", false);
            $user->set("lavaperkpermission", false);
            $user->set("heartperkpermission", false);
            $user->set("flameperkpermission", false);
            $user->set("portalperkpermission", false);
            $user->set("sporeperkpermission", false);
            $user->set("splashperkpermission", false);
            $user->set("afkmove", false);
            $user->set("afkchat", false);
            $user->set("sb", true);
            $user->set("sbcustom", false);
            $user->set("customscore", false);
            $user->set("registermarry", true);
            $user->save();
            $stats->set("joins", 0);
            $stats->set("break", 0);
            $stats->set("place", 0);
            $stats->set("drop", 0);
            $stats->set("pick", 0);
            $stats->set("interact", 0);
            $stats->set("jumps", 0);
            $stats->set("messages", 0);
            $stats->set("votes", 0);
            $stats->set("consume", 0);
            $stats->set("kicks", 0);
            $stats->set("erfolge", 0);
            $stats->set("movefly", 0);
            $stats->set("movewalk", 0);
            $stats->set("jumperfolg", false); //10000
            $stats->set("breakerfolg", false); //1000000
            $stats->set("placeerfolg", false); //1000000
            $stats->set("messageerfolg", false); //1000000
            $stats->set("joinerfolg", false); //10000
            $stats->set("kickerfolg", false); //1000
            $stats->save();
            $playersb->set("title");
            $playersb->set("l1", false);
            $playersb->set("l2", false);
            $playersb->set("l3", false);
            $playersb->set("l4", false);
            $playersb->set("l5", false);
            $playersb->set("l6", false);
            $playersb->set("l7", false);
            $playersb->set("l8", false);
            $playersb->set("l9", false);
            $playersb->set("l10", false);
            $playersb->set("l11", false);
            $playersb->set("l12", false);
            $playersb->set("l13", false);
            $playersb->set("l14", false);
            $playersb->set("l15", false);
            $playersb->set("line1", "");
            $playersb->set("line2", "");
            $playersb->set("line3", "");
            $playersb->set("line4", "");
            $playersb->set("line5", "");
            $playersb->set("line6", "");
            $playersb->set("line7", "");
            $playersb->set("line8", "");
            $playersb->set("line9", "");
            $playersb->set("line10", "");
            $playersb->set("line11", "");
            $playersb->set("line12", "");
            $playersb->set("line13", "");
            $playersb->set("line14", "");
            $playersb->set("line15", "");
            $playersb->save();


            //DiscordMessgae
            if ($dcsettings->get("DC") === true) {
                $nickname = $player->getName();
                $this->getServer()->broadcastMessage($api->getSetting("prefix") . "§e" . $player->getName() . " ist neu auf dem Server! §cWillkommen");
                $time = date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin");
                $format = "**__WILLKOMMEN__ : {time} : Spieler : {player} ist NEU auf dem Server " . $this->getServer()->getIp() . ":" . $this->getServer()->getPort() . " und ist __Herzlichst Willkommen!__**";
                $msg = str_replace("{time}", $time, str_replace("{player}", $nickname, $format));
                $this->sendMessage($nickname, $msg);
            }
            //Regeln
            if ($api->getConfig("Regeln") === true) {
                $form = new SimpleForm(function (Player $player, int $data = null) {

                    $result = $data;
                    if ($result === null) {
                        return true;
                    }
                    switch ($result) {
                        case 0:
                            $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
                            $player->sendMessage("§eWir haben auch ein Discordserver : §d" . $dcsettings->get("dclink"));
                            break;
                        case 1:
                            $player->kick("§cDu hättest dich besser entscheiden sollen :P", false);
                    }
                });
                $form->setTitle("§0======§f[§cWillkommen]§0======");
                $form->setContent("§eHerzlich willkommen " . $groups->get("nickname") . " wir wünschen dir Viel Spaß auf " . "! Bevor du loslegst zu Spielen solltest du Zuerst unsere Regeln sowie die Datenschutzgrundverordung durschlesen. Wenn du Hilfe brauchst schau einfach bei /hilfe nach dort findest du einige sachen die dir helfen können.\n\n Wir wünschen dir einen Guten Start!");
                $form->addButton("§0Alles Klar!");
                $form->addButton("§0Juckt mich Nicht");
                $form->sendToPlayer($player);
            }
        }

        //JoinMessages
        $all = $this->getServer()->getOnlinePlayers();
        $prefix = $playerdata->getNested($player->getName() . ".groupprefix");
        $slots = $api->getSetting("slots");
        $spielername = $gruppe->get("Nickname");
        if ($api->getConfig("JoinTitle") === true) { //JoinTitle
            $subtitle = str_replace("{player}", $player->getName(), $api->getConfig("Subtitlemsg"));
            $title = str_replace("{player}", $player->getName(), $api->getConfig("Titlemsg"));
            $player->sendTitle($title);
            $player->sendSubTitle($subtitle);
        }
        if ($api->getConfig("JoinTip") === true) { //JoinTip
            $tip = str_replace("{player}", $player->getName(), $api->getConfig("Tipmsg"));
            $player->sendTip($tip);
        }
        if ($api->getConfig("JoinMessage") === true) { //Joinmessage
            if ($gruppe->get("Nickname") === null) {
                $stp1 = str_replace("{player}", $player->getName(), $api->getConfig("Joinmsg"));
            } else {
                $stp1 = str_replace("{player}", $spielername, $api->getConfig("Joinmsg"));
            }
            $stp2 = str_replace("{count}", count($all), $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $joinmsg = str_replace("{prefix}", $prefix, $stp3);
            $event->setJoinMessage($joinmsg);
        } else {
            $event->setJoinMessage("");
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        //Configs
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $gruppe = new Config($this->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $playerdata = new Config($this->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $api = new BaseAPI();
        $chatprefix = $dcsettings->get("chatprefix");
        $prefix = $playerdata->getNested($player->getName() . ".groupprefix");
        $spielername = $gruppe->get("Nickname");
        $all = $this->getServer()->getOnlinePlayers();
        $playername = $event->getPlayer()->getName();
        $group = $playerdata->getNested($player->getName() . ".group");
        $slots = $api->getSetting("slots");
        //Discord
        if ($dcsettings->get("DC") === true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Quitmsg"));
            $stp2 = str_replace("{count}", count($all), $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $format = str_replace("{gruppe}", $group, $stp3);
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($format, $msg);
        }
        //QuitMessage
        if ($api->getConfig("QuitMessage") === true) {
            $stp1 = str_replace("{player}", $spielername, $api->getConfig("Quitmsg"));
            $stp2 = str_replace("{count}", count($all), $stp1);
            $stp3 = str_replace("{slots}", $slots, $stp2);
            $quitmsg = str_replace("{prefix}", $prefix, $stp3);
            $event->setQuitMessage($quitmsg);
        } else {
            $event->setQuitMessage("");
        }
        $cfg = new Config($this->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $cfg->set("afkmove", false);
        $cfg->set("afk", false);
        $cfg->save();
    }

    public function TotemEffect(Player $player)
    {
        $api = new BaseAPI();
        if ($api->getConfig("totem") === true) {
            $item = $player->getInventory()->getItemInHand();
            $player->getInventory()->setItemInHand(VanillaItems::TOTEM());
            $player->broadcastAnimation(new TotemUseAnimation($player));
            $player->getWorld()->addSound($player->getPosition(), new TotemUseSound());
            $player->getInventory()->setItemInHand($item);
        }
    }

    public function addStrike(Player $player): void
    {
        $pos = $player->getPosition();
        $light2 = AddActorPacket::create(Entity::nextRuntimeId(), 1, "minecraft:lightning_bolt", $player->getPosition()->asVector3(), null, $player->getLocation()->getYaw(), $player->getLocation()->getPitch(), 0.0, 0.0, [], [], []);
        $block = $player->getWorld()->getBlock($player->getPosition()->floor()->down());
        $particle = new BlockBreakParticle($block);
        $player->getWorld()->addParticle($pos, $particle, $player->getWorld()->getPlayers());
        $sound2 = PlaySoundPacket::create("ambient.weather.thunder", $pos->getX(), $pos->getY(), $pos->getZ(), 1, 1);
        Server::getInstance()->broadcastPackets($player->getWorld()->getPlayers(), [$light2, $sound2]);
    }

    public function particle()
    {
        $level = $this->getServer()->getWorldManager()->getDefaultWorld();
        $pos = $level->getSafeSpawn();
        $posx = -2641.5;
        $posy = 13;
        $posz = 942.5;
        $count = 100;
        //$particle = new DustParticle($pos); //, mt_rand(), mt_rand(), mt_rand(), mt_rand());
        /*for ($yaw = 0, $y = $pos->y; $y < $pos->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $x = -sin($yaw) + $pos->x;
            $z = cos($yaw) + $pos->z;
            $particle->encode($pos);
            //$particle->setCompundet($x, $y, $z);
            $level->addParticle($pos, $particle);
        }*/

        $particle = new DustParticle(Color::mix(Color::fromRGB((int)"§a")));
        for ($yaw = 0, $y = $pos->y; $y < $pos->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            //$x = -sin($yaw) + $pos->x;
            //$z = cos($yaw) + $pos->z;
            $pos1 = new Vector3(-sin($yaw) + $pos->x, $y < $pos->y + 4, cos($yaw) + $pos->z);
            $level->addParticle($pos1, $particle);
        }
    }

    #votesytem
    public function reload()
    {
        $api = new BaseAPI();
        $this->saveDefaultConfig();
        if (!is_dir($this->getDataFolder() . Main::$setup)) {
            mkdir($this->getDataFolder() . Main::$setup);
        }
        $this->lists = [];
        foreach (scandir($this->getDataFolder() . Main::$setup) as $file) {
            $ext = explode(".", $file);
            $ext = (count($ext) > 1 && isset($ext[count($ext) - 1]) ? strtolower($ext[count($ext) - 1]) : "");
            if ($ext == "vrc") {
                $this->lists[] = json_decode(file_get_contents($this->getDataFolder() . "Setup/$file"), true);
            }
        }
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $prefix = $api->getConfig("voten");

        $this->reloadConfig();
        $config = $this->getConfig()->getAll();
        $this->message = $prefix . $api->getLang("votesucces");
        $this->items = [];
        $this->debug = isset($config["Debug"]) && $config["Debug"] === true ? true : false;
    }

    public function rewardPlayer($player, $multiplier)
    {
        $api = new BaseAPI();
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $chatprefix = $dcsettings->get("chatprefix");
        $ar = getdate();

        $prefix = $api->getSetting("voten");
        if (!$player instanceof Player) {
            return;
        }
        if ($multiplier < 1) {
            $player->sendMessage($prefix . "§dVote hier -> " . $api->getConfig("VoteLink"));
            return;
        }
        $player->sendMessage($prefix . $api->getLang("votesucces") . ($multiplier == 1 ? "" : "s") . "!");
        $message = str_replace("{player}", $player->getName(), $api->getLang("votebc"));
        $this->getServer()->broadcastMessage($prefix . $player->getNameTag() . " " . $message);
        //$configs = new Config($this->getDataFolder() . Main::$statsfile . $player->getPlayerInfo()->getUsername() . ".json", Config::JSON);
        $user = new Config($this->getDataFolder() . Main::$userfile . $player->getName() . ".json", Config::JSON);
        $user->set("votepoints", $user->get("votepoints") + 1);
        $api->addCoins($player, 100);
        $api->addMoney($player, 2000);
        $api->addVotePoints($player, 1);
        $player->getInventory()->addItem(ItemFactory::getInstance()->get(ItemIds::SPONGE, 0 , 1));
        $user->save();
        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), "key vote ". $player->getName());
        //Consolen Command für key
        $player->sendMessage($prefix . "§dDu hast §e2000$ §dsowie §e100 §dCoins und §e1 §dLuckyBlock bekommen!");
        //$player->getInventory()->canAddItem();
        if ($dcsettings->get("DC") === true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Votemsg"));
            $msg = str_replace("{time}", $time, str_replace("{player}", $player->getName(), $format));
            $this->sendMessage($format, $msg);

        }
    }

    #votesystem ende

    public function onQuery(QueryRegenerateEvent $event)
    {
        $all = $this->getServer()->getOnlinePlayers();
        $count = count($all);
        $countdata = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $countdata->set("players", $count);
        $countdata->save();
        $online = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $event->getQueryInfo()->setPlayerCount($online->get("players"));


    }

    public function onPlayerLogin(PlayerLoginEvent $event)
    {
        $api = new BaseAPI();
        if ($api->getConfig("defaultspawn") === true) {
            $event->getPlayer()->teleport($this->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        }
        $this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this, $event->getPlayer()), 20);

    }


    public static function num_getOrdinal($num)
    {
        $rounded = $num % 100;
        if (3 < $rounded and $rounded < 21) {
            return "th";
        }
        $unit = $rounded % 10;
        if ($unit === 1) {
            return "st";
        }
        if ($unit === 2) {
            return "nd";
        }
        return $unit === 3 ? "rd" : "th";
    }

    public function onItemSpawn(ItemSpawnEvent $event)
    {
        $api = new BaseAPI();
        if ($api->getConfig("inames") === true) {
            $entity = $event->getEntity();
            $item = $entity->getItem();
            $name = $item->getName();
            $entity->setNameTag($name);
            $entity->setNameTagVisible(true);
            $entity->setNameTagAlwaysVisible(true);
        }
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $cfg = new Config($this->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $player = $event->getPlayer();
        self::$afksesion[$player->getName()] = $cfg->get("AFK");
    }

    public function onChat(PlayerChatEvent $event): bool
    {
        $api = new BaseAPI();
        $voteconfig = new Config($this->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $playerdata = new Config($this->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $playername = $event->getPlayer()->getName();
        self::$afksesion[$player->getName()] = $voteconfig->get("AFK");

        $prefix = $playerdata->getNested($player->getName() . ".group");
        $chatprefix = $dcsettings->get("chatprefix");
        $ar = getdate();

        $stats = new Config($this->getDataFolder() . Main::$statsfile . $player->getPlayerInfo()->getUsername() . ".json", Config::JSON);
        if ($voteconfig->get("MussVoten") === true) {
            if ($stats->get("votes") < $voteconfig->get("Mindestvotes")) {
                $player->sendMessage($api->getSetting("error") . "§cDu musst mindestens 1x Gevotet haben um auf dem Server Schreiben zu können! §f-> §e" . $voteconfig->get("VoteLink") . "\n §d->Solltest du gevotet haben nutze§f: §d/vote");
                $event->cancel();
                return true;
            } else {
                $event->uncancel();
                if ($dcsettings->get("DC") === true) {
                    $ar = getdate();
                    $time = $ar['hours'] . ":" . $ar['minutes'];
                    $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Chatmsg"));
                    $stp3 = str_replace("{msg}", $message, $stp1);
                    $format = str_replace("{gruppe}", $prefix, $stp3);
                    $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
                    $this->sendMessage($format, $msg);

                }
            }
        } elseif ($voteconfig->get("MussVoten") === false) {
            if ($dcsettings->get("DC") === true) {
                $time = $ar['hours'] . ":" . $ar['minutes'];
                $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Chatmsg"));
                $stp3 = str_replace("{msg}", $message, $stp1);
                $format = str_replace("{gruppe}", $prefix, $stp3);
                $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
                $this->sendMessage($format, $msg);
            }
        }
        $msg = $event->getMessage();
        $money = new BaseAPI();
        if ($this->win != null && $this->price != null) {
            if ($msg == $this->win) {
                $this->getServer()->broadcastMessage($api->getSetting("info") . "§7Der Spieler §6" . $player->getNameTag() . " §7hat das Wort: §e" . $this->win . " §7entschlüsselt und hat §a" . $this->price . "€ §7gewonnen!");
                $money->addMoney($player, $this->price);
                $this->win = null;
                $this->price = null;
                $event->cancel();
            }
        }
        return true;
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $api = new BaseAPI();
        $playerdata = new Config($this->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if ($dcsettings->get("DC") === true) {
            $playername = $event->getPlayer()->getName();
            $prefix = $playerdata->getNested($event->getPlayer()->getName() . ".group");
            $chatprefix = $dcsettings->get("chatprefix");
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Deathmsg"));
            $format = str_replace("{gruppe}", $prefix, $stp1);
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($format, $msg);
        }
        if ($api->getConfig("keepinventory") === true) {
            $event->setKeepInventory(true);
        } elseif ($api->getConfig("keepinventory") === false) {
            $event->setKeepInventory(false);
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $playerdata = new Config($this->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if ($dcsettings->get("DC") === true) {
            $playername = $event->getPlayer()->getName();
            $prefix = $playerdata->getNested($event->getPlayer()->getName() . ".group");
            $chatprefix = $dcsettings->get("chatprefix");
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $stp1 = str_replace("{dcprefix}", $chatprefix, $dcsettings->get("Kickmsg"));
            $format = str_replace("{gruppe}", $prefix, $stp1);
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($format, $msg);
        }
    }

    public function backFromAsync($player, $result)
    {
        if ($player === "nolog") {
            return;
        } elseif ($player === "CONSOLE") {
            $player = new ConsoleCommandSender();
        } else {
            $playerinstance = $this->getServer()->getPlayerExact($player);
            if ($playerinstance === null) {
                return;
            } else {
                $player = $playerinstance;
            }
        }
    }

    public function onMessage(CommandSender $sender, Player $receiver): void
    {
        $this->lastSent[$receiver->getName()] = $sender->getName();
    }

    public function getLastSent(string $name): string
    {
        return $this->lastSent[$name] ?? "";
    }

    public function sendMessage($player, string $msg): bool
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $name = $dcsettings->get("webhookname");
        $webhook = $dcsettings->get("webhookurl");
        $cleanMsg = $this->cleanMessage($msg);
        $curlopts = [
            "content" => $cleanMsg,
            "username" => $name
        ];
        if ($cleanMsg === "") {
            $this->getLogger()->warning("§cWarning: Empty message cannot be sent to discord.");
            return false;
        }
        if ($dcsettings->get("DC") === true) {
            $this->getServer()->getAsyncPool()->submitTask(new task\SendAsyncTask($player, $webhook, serialize($curlopts)));
        }
        return true;
    }

    public function cleanMessage(string $msg): string
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $banned = $dcsettings->get("banned_words", []);
        return str_replace($banned, '', $msg);
    }

    public function getElevators(Block $block, string $where = "", bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = DaylightSensor::class;
        } else {
            $blocks = [ItemIds::DAYLIGHT_DETECTOR, ItemIds::DAYLIGHT_DETECTOR_INVERTED];
        }
        $count = 0;
        if ($where === "up") {
            $y = $block->getPosition()->getY() + 1;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        } elseif ($where === "down") {
            $y = $block->getPosition()->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y--;
            }
        } else {
            $y = 0;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        }
        return $count;
    }


    public function getNextElevator(Block $block, string $where = "", bool $searchForPrivate = false): ?Block
    {
        if (!$searchForPrivate) {
            $blocks = [ItemIds::DAYLIGHT_DETECTOR];
        } else {
            $blocks = [ItemIds::DAYLIGHT_DETECTOR, ItemIds::DAYLIGHT_DETECTOR_INVERTED];
        }
        $elevator = null;
        if ($where === "up") {
            $y = $block->getPosition()->getY() + 1;
            while ($y < $block->getPosition()->getWorld()->getMaxY()) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y++;
            }
        } else {
            $y = $block->getPosition()->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y--;
            }
        }
        if ($elevator === null) return null;

        if ($this->config->get("checkFloor") !== true) return $elevator;

        $block1 = $elevator->getPosition()->getWorld()->getBlock(new Vector3($elevator->getPosition()->getX(), $elevator->getPosition()->getY() + 1, $elevator->getPosition()->getZ()));
        $block2 = $elevator->getPosition()->getWorld()->getBlock(new Vector3($elevator->getPosition()->getX(), $elevator->getPosition()->getY() + 2, $elevator->getPosition()->getZ()));
        if ($block1->getId() !== 0 || $block2->getId() !== 0) return $block;

        $blocksToCheck = [];

        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX() + 1, $block1->getPosition()->getY(), $block1->getPosition()->getZ()));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX() - 1, $block1->getPosition()->getY(), $block1->getPosition()->getZ()));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX(), $block1->getPosition()->getY(), $block1->getPosition()->getZ() + 1));
        $blocksToCheck[] = $block1->getPosition()->getWorld()->getBlock(new Vector3($block1->getPosition()->getX(), $block1->getPosition()->getY(), $block1->getPosition()->getZ() - 1));

        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX() + 1, $block2->getPosition()->getY(), $block2->getPosition()->getZ()));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX() - 1, $block2->getPosition()->getY(), $block2->getPosition()->getZ()));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX(), $block2->getPosition()->getY(), $block2->getPosition()->getZ() + 1));
        $blocksToCheck[] = $block2->getPosition()->getWorld()->getBlock(new Vector3($block2->getPosition()->getX(), $block2->getPosition()->getY(), $block2->getPosition()->getZ() - 1));

        $deniedBlocks = [ItemIds::LAVA, ItemIds::FLOWING_LAVA, ItemIds::WATER, ItemIds::FLOWING_WATER];
        foreach ($blocksToCheck as $blockToCheck) {
            if (in_array($blockToCheck->getId(), $deniedBlocks)) return $block;
        }

        return $elevator;
    }

    public function getFloor(Block $block, bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = [ItemIds::DAYLIGHT_SENSOR];
        } else {
            $blocks = [ItemIds::DAYLIGHT_SENSOR, ItemIds::DAYLIGHT_SENSOR_INVERTED];
        }
        $sw = 0;
        $y = -1;
        while ($y < $block->getPosition()->getWorld()->getMaxY()) {
            $y++;
            $blockToCheck = $block->getPosition()->getWorld()->getBlock(new Vector3($block->getPosition()->getX(), $y, $block->getPosition()->getZ()));
            if (!in_array($blockToCheck->getId(), $blocks)) continue;
            $sw++;
            if ($blockToCheck === $block) break;
        }
        return $sw;
    }

    //TPASystem
    public function setInvite(Player $sender, Player $target): void
    {
        $this->invite[$target->getName()] = $sender->getName();
    }

    public function getInvite($name): string
    {
        return $this->invite[$name];
    }

    public function getInviteControl(string $name): bool
    {
        return isset($this->invite[$name]);
    }

    //Configs
    public function groupsgenerate()
    {
        if (!file_exists($this->getDataFolder() . Main::$cloud . "groups.yml")) {
            $groups = new Config($this->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
            $groups->set("DefaultGroup", "normal");

            $groups->setNested("Groups.normal.groupprefix", "§f[§eSpieler§f]§7");
            $groups->setNested("Groups.normal.format1", "§f[§eSpieler§f] §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.normal.format2", "§f[§eSpieler§f] {clan} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.normal.format3", "§f[§eSpieler§f] {heirat} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.normal.format4", "§f[§eSpieler§f] {heirat} {clan} §7{name} §r§f|§7 {msg}");
            $groups->setNested("Groups.normal.nametag", "§f[§eSpieler§f] §7{name}");
            $groups->setNested("Groups.normal.displayname", "§eS§f:§7{name}");
            $groups->setNested("Groups.normal.permissions", ["CoreV6"]);

            $groups->setNested("Groups.premium.groupprefix", "§f[§6Premium§f]§6");
            $groups->setNested("Groups.premium.format1", "§f[§6Premium§f] §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format2", "§f[§6Premium§f] {clan} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format3", "§f[§6Premium§f] {heirat} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.format4", "§f[§6Premium§f] {heirat} {clan} §6{name} §r§f|§6 {msg}");
            $groups->setNested("Groups.premium.nametag", "§f[§6Premium§f] §6{name}");
            $groups->setNested("Groups.premium.displayname", "§6P§f:§6{name}");
            $groups->setNested("Groups.premium.permissions", ["CoreV6"]);

            $groups->setNested("Groups.owner.groupprefix", "§f[§4Owner§f]§c");
            $groups->setNested("Groups.owner.format1", "§f[§4Owner§f] §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format2", "§f[§4Owner§f] {clan} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format3", "§f[§4Owner§f] {heirat} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.format4", "§f[§4Owner§f] {heirat} {clan} §c{name} §r§f|§c {msg}");
            $groups->setNested("Groups.owner.nametag", "§f[§4Owner§f] §c{name}");
            $groups->setNested("Groups.owner.displayname", "§4O§f:§c{name}");
            $groups->setNested("Groups.owner.permissions", ["CoreV6"]);

            //Defaultgroup
            $groups->set("DefaultGroup", "normal");
            $groups->save();
        }
    }

    //Music Start
    public function Play($sound, $type = 0, $blo = 0)
    {
        if (is_numeric($sound) and $sound > 0) {
            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                $noteblock = $this->getNearbyNoteBlock($p->getLocation()->x, $p->getLocation()->y, $p->getLocation()->z, $p->getWorld());
                $noteblock1 = $noteblock;
                if (!empty($noteblock)) {
                    if ($this->song->name != "") {
                        $p->sendPopup("§6Spielt: §a" . $this->song->name);
                    } else {
                        $p->sendPopup("§6Spielt: §a" . $this->name);
                    }
                    $i = 0;
                    while ($i < $blo) {
                        if (current($noteblock)) {
                            next($noteblock);
                            $i++;
                        } else {
                            $noteblock = $noteblock1;
                            $i++;
                        }
                    }
                    $block = current($noteblock);
                    if ($block) {
                        //$pk = new BlockEventPacket();
                        $pk = $block;
                        $pk->eventType = $type;
                        $pk->eventData = $sound;
                        $pk = new LevelSoundEventPacket();
                        $pk->sound = LevelSoundEvent::NOTE;
                        /*$pk->x = $block->x;
                        $pk->y = $block->y;
                        $pk->z = $block->z;*/
                        $pk->position = new Vector3($p->getLocation()->x, $p->getLocation()->y, $p->getLocation()->z);
                        //$pk->volume = $type; //old
                        //$pk->pitch = $sound; //old
                        $pk->extraData = $sound; //**new changes**
                        //$pk->unknownBool = true; //old
                        //$pk->unknownBool2 = true; //old
                        $p->getNetworkSession()->sendDataPacket($pk);
                    }
                }
            }
        }
    }

    public function CheckMusic()
    {
        if ($this->getDirCount($this->getPluginDir()) > 0 and $this->RandomFile($this->getPluginDir(), "nbs")) {
            return true;
        }
        return false;
    }

    public function getDirCount($PATH)
    {
        $num = sizeof(scandir($PATH));
        $num = ($num > 2) ? $num - 2 : 0;
        return $num;
    }

    public function getPluginDir()
    {
        return $this->getServer()->getDataPath() . "plugins/songs/";
    }

    public function getRandomMusic()
    {
        $dir = $this->RandomFile($this->getPluginDir(), "nbs");
        if ($dir) {
            $api = new Music($this, $dir);
            return $api;
        }
        return false;
    }

    public function RandomFile($folder = '', $extensions = '.*')
    {
        $folder = trim($folder);
        $folder = ($folder == '') ? './' : $folder;
        if (!is_dir($folder)) {
            return false;
        }
        $files = array();
        if ($dir = @opendir($folder)) {
            while ($file = readdir($dir)) {
                if (!preg_match('/^\.+$/', $file) and
                    preg_match('/\.(' . $extensions . ')$/', $file)) {
                    $files[] = $file;
                }
            }
            closedir($dir);
        } else {
            return false;
        }
        if (count($files) == 0) {
            return false;
        }
        mt_srand((double)microtime() * 1000000);
        $rand = mt_rand(0, count($files) - 1);
        if (!isset($files[$rand])) {
            return false;
        }
        if (function_exists("icon")) {
            $rname = iconv('gbk', 'UTF-8', $files[$rand]);
        } else {
            $rname = $files[$rand];
        }
        $this->name = str_replace('.nbs', '', $rname);
        return $folder . $files[$rand];
    }

    public function getNearbyNoteBlock($x, $y, $z, $world)
    {
        $nearby = [];
        $minX = $x - 5;
        $maxX = $x + 5;
        $minY = $y - 5;
        $maxY = $y + 5;
        $minZ = $z - 2;
        $maxZ = $z + 2;

        for ($x = $minX; $x <= $maxX; ++$x) {
            for ($y = $minY; $y <= $maxY; ++$y) {
                for ($z = $minZ; $z <= $maxZ; ++$z) {
                    $v3 = new Vector3($x, $y, $z);
                    $block = $world->getBlock($v3);
                    if ($block->getID() == 25) {
                        $nearby[] = $block;
                    }
                }
            }
        }
        return $nearby;
    }

    public function getFullBlock($x, $y, $z, $level)
    {
        return $level->getChunk($x >> 4, $z >> 4, false)->getFullBlock($x & 0x0f, $y & 0x7f, $z & 0x0f);
    }

    public function StartNewTask()
    {
        $this->song = $this->getRandomMusic();
        $this->getScheduler()->cancelAllTasks($this);
        //$this->MusicPlayer = new MusicTask($this);
        $this->getScheduler()->scheduleRepeatingTask(new MusicTask($this), 1);
        //$this->getScheduler()->scheduleRepeatingTask($this->MusicPlayer, intval(floor(2990)));

    }

    //Music end

    public function getPlayerPlatform(Player $player): string
    {
        $extraData = $player->getPlayerInfo()->getExtraData();

        if ($extraData["DeviceOS"] === DeviceOS::ANDROID && $extraData["DeviceModel"] === "") {
            return "Linux";
        }

        return match ($extraData["DeviceOS"]) {
            DeviceOS::ANDROID => "Android",
            DeviceOS::IOS => "iOS",
            DeviceOS::OSX => "macOS",
            DeviceOS::AMAZON => "FireOS",
            DeviceOS::GEAR_VR => "Gear VR",
            DeviceOS::HOLOLENS => "Hololens",
            DeviceOS::WINDOWS_10 => "Windows",
            DeviceOS::WIN32 => "Windows 7 (Edu)",
            DeviceOS::DEDICATED => "Dedicated",
            DeviceOS::TVOS => "TV OS",
            DeviceOS::PLAYSTATION => "PlayStation",
            DeviceOS::NINTENDO => "Nintendo Switch",
            DeviceOS::XBOX => "Xbox",
            DeviceOS::WINDOWS_PHONE => "Windows Phone",
            default => "Unknown"
        };
    }

    //Vanish
    public function getSession(Player $player, $key)
    {
        $player = $player->getName();
        if (!isset($this->sessions["$player"]) || !isset($this->sessions["$player"]["$key"])) {
            return false;
        } else {
            return $this->sessions["$player"]["$key"];
        }
    }

    public function setSession(Player $player, $key, $value): bool
    {
        $player = $player->getName();
        if (!isset($this->sessions["$player"]) || !isset($this->sessions["$player"]["$key"])) {
            return false;
        } else {
            $this->sessions["$player"]["$key"] = $value;
            return true;
        }
    }

    public function isVanished(Player $player): bool
    {
        if (!$this->getSession($player, "vanish")) {
            return false;
        } else {
            return true;
        }
    }

    public function switchVanish(Player $player): bool
    {
        if (!$this->getSession($player, "vanish")) {
            return false;
        } else {
            if (!$this->getSession($player, "vanish")) {
                $this->setSession($player, "vanish", true);
            } else {
                $this->setSession($player, "vanish", false);
            }
            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                if ($p !== $player) {
                    $p->hidePlayer($player);
                }
            }
            return true;
        }
    }

    //VanishEnde
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(CustomPlayer::class);
    }
    //OnlineTime
    public function getDatabase(): OnlineSQLite
    {
        return $this->db;
    }

    public function getFormattedTime($t)
    {
        $f = sprintf("%02d%s%02d%s%02d", floor(abs($t) / 3600), ":", (abs($t) / 60) % 60, ":", abs($t) % 60);
        $time = explode(":", $f);
        return "§b".$time[0] . " §9hrs §b" . $time[1] . " §9mins §b" . $time[2] . " §9secs";
    }

    public function getTotalTime($pn): String
    {
        $pn = "$pn";
        $pn = strtolower($pn);
        if ($this->getServer()->getPlayerByPrefix($pn) !== null) {
            $p = $this->getServer()->getPlayerbYpREFIX($pn);
        } else $p = $pn;
        $totalsecs = $this->db->getRawTime($p);
        if ($this->getServer()->getPlayerByPrefix($pn) !== null) {
            $t = (time() - self::$times[$pn]);
        } else $t = 0;
        $t = ($t + $totalsecs);
        return ($t < 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t) / 3600), ":", (abs($t) / 60) % 60, ":", abs($t) % 60);
    }

    public function getSessionTime($pn): String
    {
        $pn = "$pn";
        $pn = strtolower($pn);
        $t = time() - self::$times[$pn];
        return ($t < 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t) / 3600), ":", (abs($t) / 60) % 60, ":", abs($t) % 60);
    }
}