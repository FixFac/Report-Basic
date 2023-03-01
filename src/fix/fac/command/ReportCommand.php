<?php

namespace fix\fac\command;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;

use fix\fac\Main;

class ReportCommand extends Command implements PluginOwned{
    
    private $plugin;

    public function __construct(Main $plugin){

        $this->plugin = $plugin;
        
        parent::__construct("report", "Reportes - FixFac", "§cUtiliza: /report", ["rpt"]);
        $this->setPermission("report.command");
        $this->setAliases(["rpt"]);

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(count($args) == 0){
            if($sender instanceof Player) {
                $this->plugin->FormRpt($sender);
            } else {
                  $sender->sendMessage("Use this command in-game");
            }
        }
        return true;
    }
    
    public function getPlugin(): Plugin{
        return $this->plugin;
    }

    public function getOwningPlugin(): Main{
        return $this->plugin;
    }
}
