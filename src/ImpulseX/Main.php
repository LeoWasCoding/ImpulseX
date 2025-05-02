<?php

namespace ImpulseX;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class Main extends PluginBase implements Listener {

    private Config $config;

    public function onEnable(): void {
        $formAPI = $this->getServer()->getPluginManager()->getPlugin("FormAPI");

        if (!$formAPI || !$formAPI->isEnabled()) {
            $this->getLogger()->warning("FormAPI not found or disabled. Disabling ImpulseX plugin.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "impulsehelp") {
            if (!$sender->hasPermission("impulse.help")) {
                $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §cYou don't have permission.");
                return true;
            }
            $this->sendHelpMessage($sender);
            return true;
        }

        if ($command->getName() === "impulsekbreload") {
            if (!$sender->hasPermission("impulse.reload")) {
                $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §cYou don't have permission.");
                return true;
            }
            $this->reloadConfig();
            $this->config = $this->getConfig();
            $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §aKnockback config reloaded.");
            return true;
        }

        if ($command->getName() === "impulsekbsettings" && $sender instanceof Player) {
            if (!$sender->hasPermission("impulse.settings")) {
                $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §cYou don't have permission.");
                return true;
            }
            $this->openKnockbackForm($sender);
            return true;
        }

        return false;
    }

    public function sendHelpMessage(CommandSender $sender): void {
        $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §aImpulseX Plugin Help:");
        $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §e/impulsehelp §7- Displays this help message.");
        $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §e/impulsekbreload §7- Reloads the knockback configuration.");
        $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §e/impulsekbsettings §7- Opens the knockback settings form.");
        $sender->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §aFor more information, visit the plugin documentation.");
    }

    public function openKnockbackForm(Player $player): void {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data === null) return;

            $horizontal = (float) $data[0];
            $vertical = (float) $data[1];

            if ($vertical > 0.4) {
                $vertical = 0.4;
                $player->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §cVertical knockback values greater than 0.4 are not allowed by PocketMine-MP and have been reset to the default.");
            }

            $this->config->set("horizontal-knockback", $horizontal);
            $this->config->set("vertical-knockback", $vertical);
            $this->config->save();

            $player->sendMessage("§7[§l§6Impulse§cX§r§7] §c➭ §aUpdated knockback values:\n§fHorizontal: §b$horizontal\n§fVertical: §b$vertical");
        });

        $form->setTitle("Knockback Settings");
        $form->addInput("Horizontal Knockback", "Enter horizontal knockback (0.0 to 5.0)", (string) $this->config->get("horizontal-knockback", 0.4));
        $form->addInput("Vertical Knockback", "Enter vertical knockback (0.0 to 5.0)", (string) $this->config->get("vertical-knockback", 0.35));
        $player->sendForm($form);
    }

    public function onDamage(EntityDamageByEntityEvent $event): void {
        $damaged = $event->getEntity();
        $damager = $event->getDamager();

        if ($damager instanceof Player) {
            $horizontal = (float) $this->config->get("horizontal-knockback", 0.4);
            $vertical = (float) $this->config->get("vertical-knockback", 0.35);

            if ($damaged instanceof Player || $damaged instanceof Entity) {
                $direction = $damaged->getPosition()->subtractVector($damager->getPosition())->normalize();
                $motion = new Vector3($direction->x * $horizontal, $vertical, $direction->z * $horizontal);
                $damaged->setMotion($motion);
            }
        }
    }
}