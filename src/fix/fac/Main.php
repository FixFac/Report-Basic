<?php

namespace fix\fac;

use pocketmine\plugin\PluginBase;
# Plugin Basico De Reporte 
# Error Sobre El Plugins Comunicar Via Discord o Suguerencia
# Discord > ! FixFac#2849
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
#Librerias
use Vecnavium\FormsUI\CustomForm;
use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
#Command
use fix\fac\command\ReportCommand;


class Main extends PluginBase {
    private $rpt;

    public function OnEnable(): void {

        $this->getLogger()->info("Reportes Activado,  Plugin Discord Creator : ! FixFac#2849");

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->rpt = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    
        $this->CargaCommands(); #Cargar El Commando Rpt
    }

    public function FormRpt($pl) {
        ###############
        $playerlist = [];
        ################
        foreach($this->getServer()->getOnlinePlayers() as $psf) {
            $playerlist[] = $psf->getName();
        }
        ###############
        $this->players[$pl->getName()] = $playerlist;
        ###############
         #rpt WebHook#
        ###############
        $form = new CustomForm(function (Player $pl, array $info = null){
            #
            if($info === null) {
              $pl->sendMessage($this->rpt->get("report-deny"));
                return true;
            }
            ##WEBHOOK Link#
            $fixweb = new Webhook($this->rpt->get("webhook-link"));
            ##########

            $menssageview = new Message();

            $menssageview->setUsername($this->rpt->get("name-Webhook"));
            ####
            $menssageview->setAvatarURL($this->rpt->get("avatar-webhook"));
            ####

            $hola = new Embed();
            ###
            $bro= $info[1];
            ###
            $hola->setTitle("Reporte");
            ##persona Que Fue Inculpada
            $hola->addField("Inculp@: ", $this->players[$pl->getName()][$bro]);
            ##Testigo osea el que lo Reporto   
            $hola->addField("Testigo: ", $pl->getName());
            
            $hola->addField("Razones: ", $info[2]);
            ########
            $menssageview->addEmbed($hola);
            $fixweb->send($menssageview); 
            $pl->sendMessage($this->rpt->get("report-accept"));
            ########
            foreach($this->getServer()->getOnlinePlayers() as $pl){
            if($pl->hasPermission("report.view")){
		        $testigo = $pl->getName();
                $pl->sendMessage("§8---------------------");
				$pl->sendMessage("§7 Inculpado: §c" . $this->players[$pl->getName()][$bro]);
				$pl->sendMessage("§7 Razón: §f" .  $info[2]);
				$pl->sendMessage("§7 Testigo: §b" . $testigo);
                $pl->sendMessage("§8---------------------");
				
			}}
        });
        #######Form Minecraft Lol#####
        $form->setTitle($this->rpt->get("title-form"));
        $form->addLabel($this->rpt->get("info-form"));

        $form->addDropdown("Jugadores: ", $this->players[$pl->getName()]);
        ################
        $form->addInput("Nota:", "Relatanos El Reporte", "Hacks?");
        ##############
        $form->sendToPlayer($pl);
        return $form;
       }
    ## Esta Funcion Te va A Permitir Cargas Commandos 
    ## Comento el Codigo Para Personas que Vean El Codigo del Plugin
       private function CargaCommands(): void{
        $cmd = [
            new ReportCommand($this)      
        ];
        $this->getServer()->getCommandMap()->registerAll("fixfac", $cmd);
    }
    
 }